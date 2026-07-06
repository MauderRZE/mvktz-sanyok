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
    public $materials, $materialId, $material_account_name, $price, $count = 1, $nomenklature_number, $contract_id;
    public $contractsList = [];
    public $isOpen = 0;

    public function render()
    {
        $this->materials = LowValueMaterial::with(['contract'])->get();
        $this->contractsList = Contract::all();
        return view('livewire.admin.low-value-material-manager');
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
        $this->materialId = null;
        $this->material_account_name = '';
        $this->price = null;
        $this->count = 1;
        $this->nomenklature_number = '';
        $this->contract_id = null;
    }

    public function store()
    {
        $this->validate([
            'material_account_name' => 'required|string|max:300',
            'price' => 'nullable|numeric|min:0',
            'count' => 'required|integer|min:1',
            'nomenklature_number' => 'nullable|string|max:100',
            'contract_id' => 'nullable|exists:contracts,id',
        ]);

        LowValueMaterial::updateOrCreate(['id' => $this->materialId], [
            'material_account_name' => $this->material_account_name,
            'price' => $this->price ?: null,
            'count' => $this->count,
            'nomenklature_number' => $this->nomenklature_number ?: null,
            'contract_id' => $this->contract_id ?: null,
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
        $this->price = $material->price;
        $this->count = $material->count;
        $this->nomenklature_number = $material->nomenklature_number;
        $this->contract_id = $material->contract_id;
        $this->openModal();
    }

    public function delete($id)
    {
        LowValueMaterial::find($id)->delete();
        session()->flash('message', 'Матеріал (МШП) видалено.');
    }
}
