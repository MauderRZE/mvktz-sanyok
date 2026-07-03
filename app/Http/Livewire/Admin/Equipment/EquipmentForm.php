<?php

namespace App\Http\Livewire\Admin\Equipment;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Equipment;
use App\Models\Contract;
use App\Models\EquipmentRetirementAct;

class EquipmentForm extends Component
{
    public ?int $equipmentId = null;
    public string $inventory_number = '';
    public string $accounting_name  = '';
    public string $status           = 'В експлуатації';
    public ?float $buy_price        = null;
    public ?int   $purchase_id      = null;
    public ?int   $retirement_act_id = null;
    public string $notes            = '';
    public bool   $isOpen           = false;

    #[On('openEquipmentForm')]
    public function create(): void
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    #[On('editEquipmentForm')]
    public function edit(int $id): void
    {
        $eq = Equipment::findOrFail($id);
        $this->equipmentId       = $id;
        $this->inventory_number  = $eq->inv_number ?? '';
        $this->accounting_name   = $eq->account_name ?? '';
        $this->status            = $eq->status ?? 'В експлуатації';
        $this->buy_price         = $eq->buy_price;
        $this->purchase_id       = $eq->purchase_id;
        $this->retirement_act_id = $eq->retirement_act_id;
        $this->notes             = $eq->notes ?? '';
        $this->isOpen = true;
    }

    public function store(): void
    {
        $this->validate([
            'inventory_number'   => 'required|string|max:100',
            'accounting_name'    => 'required|string|max:255',
            'status'             => 'required|string',
            'buy_price'          => 'nullable|numeric|min:0',
            'purchase_id'        => 'nullable|integer|exists:purchases,id',
            'retirement_act_id'  => 'nullable|integer|exists:equipment_retirement_acts,id',
            'notes'              => 'nullable|string|max:500',
        ]);

        Equipment::updateOrCreate(
            ['id' => $this->equipmentId],
            [
                'inv_number'         => $this->inventory_number,
                'account_name'       => $this->accounting_name,
                'status'             => $this->status,
                'buy_price'          => $this->buy_price ?: null,
                'purchase_id'        => $this->purchase_id ?: null,
                'retirement_act_id'  => $this->retirement_act_id ?: null,
                'notes'              => $this->notes ?: null,
            ]
        );

        session()->flash('message',
            $this->equipmentId ? 'Обладнання оновлено.' : 'Обладнання створено.');

        $this->isOpen = false;
        $this->resetInputFields();

        // Повідомити батьківський список про оновлення
        $this->dispatch('equipmentSaved');
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields(): void
    {
        $this->equipmentId       = null;
        $this->inventory_number  = '';
        $this->accounting_name   = '';
        $this->status            = 'В експлуатації';
        $this->buy_price         = null;
        $this->purchase_id       = null;
        $this->retirement_act_id = null;
        $this->notes             = '';
    }

    public function render()
    {
        $purchasesList      = Contract::all();
        $retirementActsList = EquipmentRetirementAct::all();

        return view('livewire.admin.equipment.equipment-form', compact(
            'purchasesList', 'retirementActsList'
        ));
    }
}
