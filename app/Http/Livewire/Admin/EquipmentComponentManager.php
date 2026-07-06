<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\EquipmentComponent;
use App\Models\Equipment;
use App\Models\BaseComponent;

#[Layout('layouts.admin')]
class EquipmentComponentManager extends Component
{
    public $components, $componentId, $equipment_id, $base_component_id, $notes, $serial_number, $has_network = 0, $ip_address, $mac_address, $status = 'Працює';
    public $equipmentList = [], $baseComponentsList = [];
    public $isOpen = 0;

    public function render()
    {
        $this->components = EquipmentComponent::with(['equipment', 'componentType'])->get();
        $this->equipmentList = Equipment::all();
        $this->baseComponentsList = BaseComponent::all();
        return view('livewire.admin.equipment-component-manager');
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
        $this->componentId = null;
        $this->equipment_id = null;
        $this->base_component_id = null;
        $this->notes = '';
        $this->serial_number = '';
        $this->has_network = 0;
        $this->ip_address = '';
        $this->mac_address = '';
        $this->status = 'Працює';
    }

    public function store()
    {
        $this->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'base_component_id' => 'required|exists:base_components,id',
            'notes' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:100',
            'has_network' => 'boolean',
            'ip_address' => 'nullable|ip',
            'mac_address' => 'nullable|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/',
            'status' => 'required|string|max:50',
        ]);

        EquipmentComponent::updateOrCreate(['id' => $this->componentId], [
            'equipment_id' => $this->equipment_id,
            'base_component_id' => $this->base_component_id,
            'notes' => $this->notes ?: null,
            'serial_number' => $this->serial_number ?: null,
            'ip_address' => $this->ip_address ?: null,
            'mac_address' => $this->mac_address ?: null,
            'status' => $this->status,
        ]);

        session()->flash('message', 
            $this->componentId ? 'Комплектуюче оновлено.' : 'Комплектуюче додано.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $comp = EquipmentComponent::findOrFail($id);
        $this->componentId = $id;
        $this->equipment_id = $comp->equipment_id;
        $this->base_component_id = $comp->base_component_id;
        $this->notes = $comp->notes;
        $this->serial_number = $comp->serial_number;
        $this->has_network = !empty($comp->ip_address) || !empty($comp->mac_address);
        $this->ip_address = $comp->ip_address;
        $this->mac_address = $comp->mac_address;
        $this->status = $comp->status;
        $this->openModal();
    }

    public function delete($id)
    {
        EquipmentComponent::find($id)->delete();
        session()->flash('message', 'Комплектуюче видалено.');
    }
}
