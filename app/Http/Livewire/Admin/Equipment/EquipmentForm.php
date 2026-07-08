<?php

namespace App\Http\Livewire\Admin\Equipment;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Equipment;
use App\Models\Contract;
use App\Models\EquipmentRetirementAct;
use App\Livewire\Forms\EquipmentLiveForm;

class EquipmentForm extends Component
{
    public EquipmentLiveForm $form;
    
    public bool $isOpen = false;

    #[On('openEquipmentForm')]
    public function create(): void
    {
        $this->form->reset();
        $this->isOpen = true;
    }

    #[On('editEquipmentForm')]
    public function edit(int $id): void
    {
        $eq = Equipment::findOrFail($id);
        $this->form->setEquipment($eq);
        $this->isOpen = true;
    }

    public function store(): void
    {
        $isUpdate = $this->form->store();

        session()->flash('message',
            $isUpdate ? 'Обладнання оновлено.' : 'Обладнання створено.');

        $this->isOpen = false;

        // Повідомити батьківський список про оновлення
        $this->dispatch('equipmentSaved');
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->form->reset();
    }

    public function render()
    {
        $purchasesList      = $this->isOpen ? Contract::all() : collect();
        $retirementActsList = $this->isOpen ? EquipmentRetirementAct::all() : collect();

        return view('livewire.admin.equipment.equipment-form', compact(
            'purchasesList', 'retirementActsList'
        ));
    }
}
