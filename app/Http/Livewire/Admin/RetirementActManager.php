<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\EquipmentRetirementAct;
use App\Livewire\Forms\RetirementActForm;

#[Layout('layouts.admin')]
class RetirementActManager extends Component
{
    public RetirementActForm $form;
    
    public $acts;
    public $isOpen = 0;

    public $search = '';

    public function render()
    {
        $this->acts = EquipmentRetirementAct::when($this->search, function($q) {
            $search = '%' . $this->search . '%';
            $q->where(function($sub) use ($search) {
                $sub->where('act_number', 'like', $search)
                    ->orWhere('reason', 'like', $search);
            });
        })
        ->orderBy('id', 'desc')
        ->get();
        return view('livewire.admin.retirement-act-manager');
    }

    public function resetFilters()
    {
        $this->search = '';
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
            $isUpdate ? 'Акт списання оновлено.' : 'Акт списання створено.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $act = EquipmentRetirementAct::findOrFail($id);
        $this->form->setAct($act);
        $this->openModal();
    }

    public function delete($id)
    {
        EquipmentRetirementAct::find($id)->delete();
        session()->flash('message', 'Акт списання видалено.');
    }
}
