<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Equipment;
use App\Models\EquipmentType;

class EquipmentManager extends Component
{
    public $equipments, $equipmentId, $inventory_number, $accounting_name, $equipment_type_id, $status;
    public $types;
    public $isOpen = 0;

    public function render()
    {
        $this->equipments = Equipment::with('type')->get();
        $this->types = EquipmentType::all();
        return view('livewire.admin.equipment-manager')->layout('layouts.admin');
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
        $this->equipmentId = null;
        $this->inventory_number = '';
        $this->accounting_name = '';
        $this->equipment_type_id = '';
        $this->status = 'В експлуатації';
    }

    public function store()
    {
        $this->validate([
            'inventory_number' => 'required',
            'accounting_name' => 'required',
            'equipment_type_id' => 'required|integer',
            'status' => 'required',
        ]);

        Equipment::updateOrCreate(['id' => $this->equipmentId], [
            'inventory_number' => $this->inventory_number,
            'accounting_name' => $this->accounting_name,
            'equipment_type_id' => $this->equipment_type_id,
            'status' => $this->status
        ]);

        session()->flash('message', 
            $this->equipmentId ? 'Обладнання оновлено.' : 'Обладнання створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $eq = Equipment::findOrFail($id);
        $this->equipmentId = $id;
        $this->inventory_number = $eq->inventory_number;
        $this->accounting_name = $eq->accounting_name;
        $this->equipment_type_id = $eq->equipment_type_id;
        $this->status = $eq->status;
        $this->openModal();
    }

    public function delete($id)
    {
        Equipment::find($id)->delete();
        session()->flash('message', 'Обладнання видалено.');
    }
}
