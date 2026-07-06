<?php

namespace App\Http\Livewire\Admin\Equipment;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Equipment;

class EquipmentDetail extends Component
{
    public ?int $equipmentId   = null;
    public mixed $equipment    = null;
    public bool  $isOpen       = false;

    #[On('openEquipmentDetail')]
    public function open(int $id): void
    {
        $this->equipmentId = $id;

        // Важкий eager-load виконується ЛИШЕ при відкритті slide-over
        $this->equipment = Equipment::with([
            'assets.componentType',
            'assets.holder.organization',
            'movements.employee.department',
            'maintenanceLogs',
            'lowValueMaterials.contract',
            'contract',
            'retirementAct',
        ])->findOrFail($id);

        $this->isOpen = true;
    }

    public function close(): void
    {
        $this->isOpen      = false;
        $this->equipmentId = null;
        $this->equipment   = null;
    }

    public function render()
    {
        return view('livewire.admin.equipment.equipment-detail');
    }
}
