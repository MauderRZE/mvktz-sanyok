<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Models\Supplier;

class SupplierForm extends Form
{
    public ?int $supplierId = null;

    public string $supplier_name = '';

    #[Validate('nullable|exists:supplier_types,id')]
    public ?int $supplier_type_id = null;

    #[Validate('nullable|string|max:20')]
    public string $tax_code = '';

    public function setSupplier(Supplier $sup)
    {
        $this->supplierId = $sup->id;
        $this->supplier_name = $sup->supplier_name;
        $this->supplier_type_id = $sup->supplier_type_id;
        $this->tax_code = $sup->tax_code ?? '';
    }

    public function store()
    {
        $this->validate([
            'supplier_name' => 'required|unique:suppliers,supplier_name,' . $this->supplierId,
        ]);

        Supplier::updateOrCreate(['id' => $this->supplierId], [
            'supplier_name' => $this->supplier_name,
            'supplier_type_id' => $this->supplier_type_id ?: null,
            'tax_code' => $this->tax_code ?: null,
        ]);

        $isUpdate = $this->supplierId !== null;
        $this->reset();
        
        return $isUpdate;
    }
}
