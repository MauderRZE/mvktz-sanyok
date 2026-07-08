<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Models\Equipment;

class EquipmentLiveForm extends Form
{
    public ?int $equipmentId = null;

    #[Validate('required|string|max:100')]
    public string $inv_number = '';

    #[Validate('required|string|max:255')]
    public string $account_name  = '';

    #[Validate('required|string')]
    public string $status = 'В експлуатації';

    #[Validate('nullable|numeric|min:0')]
    public ?float $buy_price = null;

    #[Validate('nullable|integer|exists:purchases,id')]
    public ?int $purchase_id = null;

    #[Validate('nullable|integer|exists:equipment_retirement_acts,id')]
    public ?int $retirement_act_id = null;

    #[Validate('nullable|string|max:500')]
    public string $notes = '';

    public function setEquipment(Equipment $eq)
    {
        $this->equipmentId = $eq->id;
        $this->inv_number = $eq->inv_number ?? '';
        $this->account_name = $eq->account_name ?? '';
        $this->status = $eq->status ?? 'В експлуатації';
        $this->buy_price = $eq->buy_price;
        $this->purchase_id = $eq->purchase_id;
        $this->retirement_act_id = $eq->retirement_act_id;
        $this->notes = $eq->notes ?? '';
    }

    public function store()
    {
        $this->validate();

        Equipment::updateOrCreate(
            ['id' => $this->equipmentId],
            [
                'inv_number'         => $this->inv_number,
                'account_name'       => $this->account_name,
                'status'             => $this->status,
                'buy_price'          => $this->buy_price ?: null,
                'purchase_id'        => $this->purchase_id ?: null,
                'retirement_act_id'  => $this->retirement_act_id ?: null,
                'notes'              => $this->notes ?: null,
            ]
        );

        $isUpdate = $this->equipmentId !== null;
        $this->reset();
        
        return $isUpdate;
    }
}
