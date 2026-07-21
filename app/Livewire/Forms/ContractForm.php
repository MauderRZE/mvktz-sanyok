<?php

namespace App\Livewire\Forms;

use App\Models\Contract;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ContractForm extends Form
{
    public ?int $contractId = null;

    #[Validate('required')]
    public string $contract_number = '';

    #[Validate('required|date_format:Y-m-d|after_or_equal:1900-01-01|before_or_equal:2099-12-31')]
    public string $contract_date = '';

    #[Validate('required|exists:suppliers,id')]
    public ?int $supplier_id = null;

    #[Validate('nullable|url|max:2048')]
    public string $contract_link = '';

    public function setContract(Contract $contract)
    {
        $this->contractId = $contract->id;
        $this->contract_number = $contract->contract_number;
        $this->contract_date = $contract->contract_date;
        $this->supplier_id = $contract->supplier_id;
        $this->contract_link = $contract->contract_link ?? '';
    }

    public function store()
    {
        $this->validate();

        Contract::updateOrCreate(['id' => $this->contractId], [
            'contract_number' => $this->contract_number,
            'contract_date' => $this->contract_date,
            'supplier_id' => $this->supplier_id,
            'contract_link' => $this->contract_link ?: null,
        ]);

        $isUpdate = $this->contractId !== null;
        $this->reset();

        return $isUpdate;
    }
}
