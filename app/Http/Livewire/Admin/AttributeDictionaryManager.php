<?php

namespace App\Http\Livewire\Admin;

use App\Livewire\Forms\AttributeDictionaryForm;
use App\Models\AttributeDictionary;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AttributeDictionaryManager extends Component
{
    use WithPagination;

    public AttributeDictionaryForm $form;

    public $isOpen = false;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = AttributeDictionary::when($this->search, function ($q) {
            $q->where('name', 'like', '%'.$this->search.'%');
        })
            ->orderBy('id', 'desc');

        return view('livewire.admin.attribute-dictionary-manager', [
            'dictAttributes' => $query->paginate(15),
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
            $isUpdate ? 'Атрибут оновлено.' : 'Атрибут створено.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $attribute = AttributeDictionary::findOrFail($id);
        $this->form->setAttribute($attribute);
        $this->openModal();
    }

    public function delete($id)
    {
        AttributeDictionary::findOrFail($id)->delete();
        session()->flash('message', 'Атрибут видалено.');
    }
}
