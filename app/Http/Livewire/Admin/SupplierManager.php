<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Supplier;

#[Layout('layouts.admin')]
class SupplierManager extends Component
{
    public $suppliers, $supplierId, $supplier_name;
    public $isOpen = 0;

    public function render()
    {
        $this->suppliers = Supplier::all();
        return view('livewire.admin.supplier-manager');
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
    }

    public function store()
    {
        $this->validate([
            'supplier_name' => 'required|unique:suppliers,supplier_name,' . $this->supplierId,
        ]);

        Supplier::updateOrCreate(['id' => $this->supplierId], [
            'supplier_name' => $this->supplier_name
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
        $this->openModal();
    }

    public function delete($id)
    {
        Supplier::find($id)->delete();
        session()->flash('message', 'Постачальника видалено.');
    }
}
