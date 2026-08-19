<?php

namespace App\Http\Livewire\Admin\Equipment;

use App\Models\Asset;
use App\Models\EquipmentMovement;
use App\Models\Location;
use App\Models\LocationHolder;
use Livewire\Attributes\On;
use Livewire\Component;

class EquipmentMoveModal extends Component
{
    public $isOpen = false;

    public $targetId; // ID of Asset

    public $targetName = '';

    public $location_id;

    public $holder_id;

    public $action_date;


    public function mount()
    {
        $this->action_date = date('Y-m-d\TH:i:s');
    }

    #[On('openMoveAsset')]
    public function openForAsset($id)
    {
        $this->resetInputFields();
        $this->targetId = $id;
        $asset = Asset::with(['componentType', 'equipment', 'lowValueMaterial'])->findOrFail($id);

        $invPart = '';
        if ($asset->lowValueMaterial && $asset->lowValueMaterial->nomenklature_number) {
            $invPart = ' | Інв/Ном: '.$asset->lowValueMaterial->nomenklature_number;
        } elseif ($asset->equipment && $asset->equipment->inv_number) {
            $invPart = ' | Інв. №: '.$asset->equipment->inv_number;
        }

        $this->targetName = ($asset->componentType->component_name ?? 'Комплектуюча').' (S/N: '.($asset->serial_number ?: 'немає').$invPart.')';

        $this->location_id = $asset->current_loc_id;
        $this->holder_id = $asset->current_holder_id;

        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->location_id = null;
        $this->holder_id = null;
        $this->action_date = date('Y-m-d\TH:i:s');
        $this->targetName = '';
        $this->targetId = null;
    }

    public function store()
    {
        if ($this->holder_id === '') {
            $this->holder_id = null;
        }
        if ($this->location_id === '') {
            $this->location_id = null;
        }

        $this->validate([
            'location_id' => 'required|exists:locations,id',
            'holder_id' => 'nullable|exists:location_holders,id',
            'action_date' => 'required|date',
        ]);

        $toHolder = $this->holder_id ? LocationHolder::find($this->holder_id) : null;

        $asset = Asset::findOrFail($this->targetId);
        $this->moveAsset($asset, $toHolder);
        session()->flash('message', 'Комплектуючу успішно переміщено.');
        $this->dispatch('assetSaved'); // refresh parent

        $this->closeModal();
    }

    private function moveAsset($asset, $toHolder)
    {
        $from_holder_id = $asset->current_holder_id;

        $asset->update([
            'current_loc_id' => $this->location_id,
            'current_holder_id' => $toHolder?->id,
        ]);

        EquipmentMovement::create([
            'equip_id' => $asset->equipment_id,
            'asset_id' => $asset->id,
            'location_id' => $this->location_id,
            'from_holder_id' => $from_holder_id,
            'to_holder_id' => $toHolder?->id,
            'employee_id' => $toHolder?->employee_id,
            'action_date' => $this->action_date ? str_replace('T', ' ', $this->action_date) : date('Y-m-d H:i:s'),
        ]);
    }

    public function render()
    {
        $locationsList = $this->isOpen ? Location::all() : collect();
        $holdersList = $this->isOpen ? LocationHolder::with(['employee', 'organization'])->get() : collect();

        return view('livewire.admin.equipment.equipment-move-modal', compact(
            'locationsList', 'holdersList'
        ));
    }
}
