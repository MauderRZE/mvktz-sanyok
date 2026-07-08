<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\AttributeDictionary;
use App\Livewire\Forms\AttributeDictionaryForm;

#[Layout('layouts.admin')]
class AttributeDictionaryManager extends Component
{
    public AttributeDictionaryForm $form;

    public $dictAttributes;
    public $isOpen = false;

    public $search = '';

    public function render()
    {
        $this->dictAttributes = AttributeDictionary::when($this->search, function($q) {
            $q->where('name', 'like', '%' . $this->search . '%');
        })
        ->orderBy('id', 'desc')
        ->get();
        return view('livewire.admin.attribute-dictionary-manager');
    }

    public function resetFilters()
    {
        $this->search = '';
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
