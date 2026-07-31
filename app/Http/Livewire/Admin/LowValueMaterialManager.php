<?php

namespace App\Http\Livewire\Admin;

use App\Models\Contract;
use App\Models\LowValueMaterial;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class LowValueMaterialManager extends Component
{
    public $materials;

    public $materialId;

    public $material_account_name;

    public $price;

    public $count = 1;

    public $nomenklature_number;

    public $contract_id;

    public $contractsList = [];

    public $isOpen = 0;

    public $search = '';

    public $filterContract = [];

    public function render()
    {
        $query = LowValueMaterial::with(['contract'])
            ->when($this->search, function ($q) {
                $search = '%'.$this->search.'%';
                $q->where(function ($sub) use ($search) {
                    $sub->where('material_account_name', 'like', $search)
                        ->orWhere('nomenklature_number', 'like', $search);
                });
            })
            ->when(! empty($this->filterContract), function ($q) {
                $hasNull = in_array('null', $this->filterContract, true) || in_array(null, $this->filterContract, true);
                $ids = array_filter($this->filterContract, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($ids, $hasNull) {
                    if (! empty($ids)) {
                        $sub->whereIn('contract_id', $ids);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('contract_id');
                    }
                });
            })
            ->orderBy('id', 'desc');

        $this->materials = $query->get();
        $this->contractsList = Contract::with('supplier')->get();

        return view('livewire.admin.low-value-material-manager');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterContract = [];
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
            'contract_id' => 'nullable|exists:purchases,id',
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
