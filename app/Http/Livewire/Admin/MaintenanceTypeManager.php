<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\MaintenanceType;

class MaintenanceTypeManager extends Component
{
    public $types, $typeId, $type_name;
    public $isOpen = 0;

    public function render()
    {
        $this->types = MaintenanceType::all();
        return view('livewire.admin.maintenance-type-manager')->layout('layouts.admin');
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
        $this->typeId = null;
        $this->type_name = '';
    }

    public function store()
    {
        $this->validate([
            'type_name' => 'required|unique:maintenance_types,type_name,' . $this->typeId,
        ]);

        MaintenanceType::updateOrCreate(['id' => $this->typeId], [
            'type_name' => $this->type_name
        ]);

        session()->flash('message', 
            $this->typeId ? 'Тип обслуговування оновлено.' : 'Тип обслуговування створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $type = MaintenanceType::findOrFail($id);
        $this->typeId = $id;
        $this->type_name = $type->type_name;
        $this->openModal();
    }

    public function delete($id)
    {
        MaintenanceType::find($id)->delete();
        session()->flash('message', 'Тип обслуговування видалено.');
    }
}
