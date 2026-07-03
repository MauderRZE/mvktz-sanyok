<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\TypeRequirement;
use App\Models\EquipmentType;
use App\Models\BaseComponent;

#[Layout('layouts.admin')]
class TypeRequirementManager extends Component
{
    public $requirements, $equipment_type_id, $component_id;
    public $orig_equipment_type_id, $orig_component_id; // For tracking edit targets
    public $typesList = [], $componentsList = [];
    public $isOpen = 0;
    public $isEditMode = false;

    public function render()
    {
        $this->requirements = TypeRequirement::with(['equipmentType', 'component'])->get();
        $this->typesList = EquipmentType::all();
        $this->componentsList = BaseComponent::all();
        return view('livewire.admin.type-requirement-manager');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
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
        $this->equipment_type_id = null;
        $this->component_id = null;
        $this->orig_equipment_type_id = null;
        $this->orig_component_id = null;
    }

    public function store()
    {
        $this->validate([
            'equipment_type_id' => 'required|exists:equipment_types,id',
            'component_id' => 'required|exists:base_components,id',
        ]);

        if ($this->isEditMode) {
            // Delete old record
            TypeRequirement::where('equipment_type_id', $this->orig_equipment_type_id)
                ->where('component_id', $this->orig_component_id)
                ->delete();
        }

        // Check if new combination already exists
        $exists = TypeRequirement::where('equipment_type_id', $this->equipment_type_id)
            ->where('component_id', $this->component_id)
            ->exists();

        if ($exists) {
            session()->flash('error', 'Ця комбінація шаблону комплектації вже існує.');
            return;
        }

        TypeRequirement::create([
            'equipment_type_id' => $this->equipment_type_id,
            'component_id' => $this->component_id,
        ]);

        session()->flash('message', 
            $this->isEditMode ? 'Шаблон комплектації оновлено.' : 'Вимогу комплектації додано.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($typeId, $compId)
    {
        $this->isEditMode = true;
        $this->orig_equipment_type_id = $typeId;
        $this->orig_component_id = $compId;
        
        $this->equipment_type_id = $typeId;
        $this->component_id = $compId;
        
        $this->openModal();
    }

    public function delete($typeId, $compId)
    {
        TypeRequirement::where('equipment_type_id', $typeId)
            ->where('component_id', $compId)
            ->delete();
            
        session()->flash('message', 'Вимогу комплектації видалено.');
    }
}
