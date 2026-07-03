<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\SupplierType;

#[Layout('layouts.admin')]
class SupplierTypeManager extends Component
{
    public $types, $typeId, $type_name;
    public $isOpen = false;

    public function render()
    {
        $this->types = SupplierType::all();
        return view('livewire.admin.supplier-type-manager');
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

    private function resetInputFields()
    {
        $this->typeId = null;
        $this->type_name = '';
    }

    public function store()
    {
        $this->validate([
            'type_name' => 'required|string|max:20',
        ]);

        SupplierType::updateOrCreate(['id' => $this->typeId], [
            'type_name' => $this->type_name
        ]);

        session()->flash('message', 
            $this->typeId ? 'Тип оновлено.' : 'Тип створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $type = SupplierType::findOrFail($id);
        $this->typeId = $id;
        $this->type_name = $type->type_name;
        $this->openModal();
    }

    public function delete($id)
    {
        SupplierType::findOrFail($id)->delete();
        session()->flash('message', 'Тип видалено.');
    }
}
