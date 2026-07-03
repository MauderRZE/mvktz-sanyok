<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Contract;
use App\Models\Supplier;

#[Layout('layouts.admin')]
class ContractManager extends Component
{
    public $contracts, $contractId, $contract_number, $contract_date, $supplier_id;
    public $suppliersList = [];
    public $isOpen = 0;

    public function render()
    {
        $this->contracts = Contract::with('supplier')->get();
        $this->suppliersList = Supplier::all();
        return view('livewire.admin.contract-manager');
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
        $this->contractId = null;
        $this->contract_number = '';
        $this->contract_date = '';
        $this->supplier_id = null;
    }

    public function store()
    {
        $this->validate([
            'contract_number' => 'required',
            'contract_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        Contract::updateOrCreate(['id' => $this->contractId], [
            'contract_number' => $this->contract_number,
            'contract_date' => $this->contract_date,
            'supplier_id' => $this->supplier_id,
        ]);

        session()->flash('message', 
            $this->contractId ? 'Договір оновлено.' : 'Договір створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $contract = Contract::findOrFail($id);
        $this->contractId = $id;
        $this->contract_number = $contract->contract_number;
        $this->contract_date = $contract->contract_date;
        $this->supplier_id = $contract->supplier_id;
        $this->openModal();
    }

    public function delete($id)
    {
        Contract::find($id)->delete();
        session()->flash('message', 'Договір видалено.');
    }
}
