<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Supplier;

#[Layout('layouts.admin')]
class SupplierManager extends Component
{
    public $suppliers, $supplierId, $supplier_name, $supplier_type_id, $tax_code;
    public $isOpen = 0;

    public $search = '';
    public $filterType = [];

    public function render()
    {
        $query = Supplier::with('supplierType')
            ->when($this->search, function($q) {
                $search = '%' . $this->search . '%';
                $q->where(function($sub) use ($search) {
                    $sub->where('supplier_name', 'like', $search)
                        ->orWhere('tax_code', 'like', $search);
                });
            })
            ->when(!empty($this->filterType), function($q) {
                $q->whereIn('supplier_type_id', $this->filterType);
            })
            ->orderBy('id', 'desc');

        $this->suppliers = $query->get();
        return view('livewire.admin.supplier-manager', [
            'supplierTypes' => \App\Models\SupplierType::all(),
        ]);
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterType = [];
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
        $this->supplierId = null;
        $this->supplier_name = '';
        $this->supplier_type_id = '';
        $this->tax_code = '';
    }

    public function store()
    {
        $this->validate([
            'supplier_name' => 'required|unique:suppliers,supplier_name,' . $this->supplierId,
            'supplier_type_id' => 'nullable|exists:supplier_types,id',
            'tax_code' => 'nullable|string|max:20',
        ]);

        Supplier::updateOrCreate(['id' => $this->supplierId], [
            'supplier_name' => $this->supplier_name,
            'supplier_type_id' => $this->supplier_type_id ?: null,
            'tax_code' => $this->tax_code ?: null,
        ]);

        session()->flash('message', 
            $this->supplierId ? 'Постачальника оновлено.' : 'Постачальника створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $sup = Supplier::findOrFail($id);
        $this->supplierId = $id;
        $this->supplier_name = $sup->supplier_name;
        $this->supplier_type_id = $sup->supplier_type_id;
        $this->tax_code = $sup->tax_code;
        $this->openModal();
    }

    public function delete($id)
    {
        Supplier::find($id)->delete();
        session()->flash('message', 'Постачальника видалено.');
    }
}
