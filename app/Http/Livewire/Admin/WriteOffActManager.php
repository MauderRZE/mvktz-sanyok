<?php

namespace App\Http\Livewire\Admin;

use App\Livewire\Forms\WriteOffActForm;
use App\Models\LowValueWriteOffAct;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class WriteOffActManager extends Component
{
    use WithPagination;

    public WriteOffActForm $form;

    public $isOpen = 0;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = LowValueWriteOffAct::when($this->search, function ($q) {
            $q->where('act_number', 'like', '%'.$this->search.'%');
        })
            ->orderBy('id', 'desc');

        return view('livewire.admin.write-off-act-manager', [
            'acts' => $query->paginate(15),
        ]);
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function create()
    {
        $this->form->reset();
        $this->form->act_date = date('Y-m-d');
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function store()
    {
        $isUpdate = $this->form->store();

        session()->flash('message',
            $isUpdate ? 'Акт списання малоцінки оновлено.' : 'Акт списання малоцінки створено.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $act = LowValueWriteOffAct::findOrFail($id);
        $this->form->setAct($act);
        $this->openModal();
    }

    public function delete($id)
    {
        LowValueWriteOffAct::find($id)->delete();
        session()->flash('message', 'Акт списання малоцінки видалено.');
    }
}
