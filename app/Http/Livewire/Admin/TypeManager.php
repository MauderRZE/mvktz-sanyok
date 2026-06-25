<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\EquipmentType;

class TypeManager extends Component
{
    public $types, $typeId, $type_name;
    public $isOpen = 0;

    public function render()
    {
        $this->types = EquipmentType::all();
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
    }

    public function store()
    {
        $this->validate([
            'type_name' => 'required',
        ]);

        EquipmentType::updateOrCreate(['id' => $this->typeId], [
            'type_name' => $this->type_name
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
        $this->openModal();
    }

    public function delete($id)
    {
        EquipmentType::find($id)->delete();
        session()->flash('message', 'Тип видалено.');
    }
}
