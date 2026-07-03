<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\AttributeDictionary;

#[Layout('layouts.admin')]
class AttributeDictionaryManager extends Component
{
    public $dictAttributes, $attributeId, $name;
    public $isOpen = false;

    public function render()
    {
        $this->dictAttributes = AttributeDictionary::all();
        return view('livewire.admin.attribute-dictionary-manager');
    }

    public function create()
    {
        $this->resetInputFields();
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

    private function resetInputFields()
    {
        $this->attributeId = null;
        $this->name = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:100',
        ]);

        AttributeDictionary::updateOrCreate(['id' => $this->attributeId], [
            'name' => $this->name
        ]);

        session()->flash('message', 
            $this->attributeId ? 'Атрибут оновлено.' : 'Атрибут створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $attribute = AttributeDictionary::findOrFail($id);
        $this->attributeId = $id;
        $this->name = $attribute->name;
        $this->openModal();
    }

    public function delete($id)
    {
        AttributeDictionary::findOrFail($id)->delete();
        session()->flash('message', 'Атрибут видалено.');
    }
}
