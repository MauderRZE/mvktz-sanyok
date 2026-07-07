<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Contract;
use App\Models\Supplier;

#[Layout('layouts.admin')]
class ContractManager extends Component
{
    public $contracts, $contractId, $contract_number, $contract_date, $supplier_id, $contract_link;
    public $suppliersList = [];
    public $isOpen = 0;

    public $search = '';
    public $filterSupplier = [];

    public function render()
    {
        $query = Contract::with('supplier')
            ->when($this->search, function($q) {
                $search = '%' . $this->search . '%';
                $q->where(function($sub) use ($search) {
                    $sub->where('contract_number', 'like', $search)
                        ->orWhereHas('supplier', function($sup) use ($search) {
                            $sup->where('supplier_name', 'like', $search);
                        });
                });
            })
            ->when(!empty($this->filterSupplier), function($q) {
                $q->whereIn('supplier_id', $this->filterSupplier);
            })
            ->orderBy('id', 'desc');

        $this->contracts = $query->get();
        $this->suppliersList = Supplier::all();
        return view('livewire.admin.contract-manager');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterSupplier = [];
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
        $this->contract_link = '';
    }

    public function store()
    {
        $this->validate([
            'contract_number' => 'required',
            'contract_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'contract_link' => 'nullable|url|max:2048',
        ]);

        Contract::updateOrCreate(['id' => $this->contractId], [
            'contract_number' => $this->contract_number,
            'contract_date' => $this->contract_date,
            'supplier_id' => $this->supplier_id,
            'contract_link' => $this->contract_link ?: null,
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
        $this->contract_link = $contract->contract_link;
        $this->openModal();
    }

    public function delete($id)
    {
        Contract::find($id)->delete();
        session()->flash('message', 'Договір видалено.');
    }
}
