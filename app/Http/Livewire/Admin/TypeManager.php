<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\EquipmentType;
use App\Models\EquipmentCategory;

class TypeManager extends Component
{
    public $types, $categories, $typeId, $type_name, $category_id;
    public $isOpen = 0;

    public function render()
    {
        $this->types = EquipmentType::with('category')->get();
        $this->categories = EquipmentCategory::all();
        return view('livewire.admin.type-manager')->layout('layouts.admin');
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

    private function resetInputFields(){
        $this->typeId = null;
        $this->type_name = '';
        $this->category_id = null;
    }

    public function store()
    {
        $this->validate([
            'type_name' => 'required',
            'category_id' => 'required',
        ]);

        EquipmentType::updateOrCreate(['id' => $this->typeId], [
            'type_name' => $this->type_name,
            'category_id' => $this->category_id,
        ]);

        session()->flash('message', 
            $this->typeId ? 'Тип оновлено.' : 'Тип створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $type = EquipmentType::findOrFail($id);
        $this->typeId = $id;
        $this->type_name = $type->type_name;
        $this->category_id = $type->category_id;
        $this->openModal();
    }

    public function delete($id)
    {
        EquipmentType::find($id)->delete();
        session()->flash('message', 'Тип видалено.');
    }
}
