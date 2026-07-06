<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\LowValueMaterial;
use App\Models\Equipment;
use App\Models\Contract;

#[Layout('layouts.admin')]
class LowValueMaterialManager extends Component
{
    public $materials, $materialId, $material_account_name, $equipment_id, $contract_id, $serial_number, $purchase_date, $installation_date, $quantity = 1, $notes;
    public $brand_model, $nomenclature_number, $status = 'На складі';
    public $equipmentList = [], $contractsList = [];
    public $isOpen = 0;

    public function render()
    {
        $this->materials = LowValueMaterial::with(['equipment', 'contract'])->get();
        $this->equipmentList = Equipment::all();
        $this->contractsList = Contract::all();
        return view('livewire.admin.low-value-material-manager');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->purchase_date = date('Y-m-d');
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
        $this->materialId = null;
        $this->material_account_name = '';
        $this->brand_model = '';
        $this->equipment_id = null;
        $this->contract_id = null;
        $this->serial_number = '';
        $this->nomenclature_number = '';
        $this->purchase_date = '';
        $this->installation_date = '';
        $this->quantity = 1;
        $this->notes = '';
        $this->status = 'На складі';
    }

    public function store()
    {
        $this->validate([
            'material_account_name' => 'required|string|max:300',
            'brand_model' => 'nullable|string|max:150',
            'equipment_id' => 'nullable|exists:equipment,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'serial_number' => 'nullable|string|max:100',
            'nomenclature_number' => 'nullable|string|max:150',
            'purchase_date' => 'nullable|date',
            'installation_date' => 'nullable|date',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|max:50',
        ]);

        LowValueMaterial::updateOrCreate(['id' => $this->materialId], [
            'material_account_name' => $this->material_account_name,
            'brand_model' => $this->brand_model ?: null,
            'equipment_id' => $this->equipment_id ?: null,
            'contract_id' => $this->contract_id ?: null,
            'serial_number' => $this->serial_number ?: null,
            'nomenclature_number' => $this->nomenclature_number ?: null,
            'purchase_date' => $this->purchase_date ?: null,
            'installation_date' => $this->installation_date ?: null,
            'quantity' => $this->quantity,
            'notes' => $this->notes ?: null,
            'status' => $this->status ?: 'На складі',
        ]);

        session()->flash('message', 
            $this->materialId ? 'Матеріал (МШП) оновлено.' : 'Матеріал (МШП) додано.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $material = LowValueMaterial::findOrFail($id);
        $this->materialId = $id;
        $this->material_account_name = $material->material_account_name;
        $this->brand_model = $material->brand_model;
        $this->equipment_id = $material->equipment_id;
        $this->contract_id = $material->contract_id;
        $this->serial_number = $material->serial_number;
        $this->nomenclature_number = $material->nomenclature_number;
        $this->purchase_date = $material->purchase_date;
        $this->installation_date = $material->installation_date;
        $this->quantity = $material->quantity;
        $this->notes = $material->notes;
        $this->status = $material->status;
        $this->openModal();
    }

    public function delete($id)
    {
        LowValueMaterial::find($id)->delete();
        session()->flash('message', 'Матеріал (МШП) видалено.');
    }
}
