<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\ItemProperty;
use App\Models\Asset;
use App\Models\LowValueMaterial;
use App\Models\AttributeDictionary;

#[Layout('layouts.admin')]
class ItemPropertyManager extends Component
{
    public $properties, $propertyId, $asset_id, $nomenclature_id, $attribute_id, $attr_value;
    public $assets, $materials, $dictAttributes;
    public $isOpen = false;

    public $search = '';
    public $filterAttribute = [];

    public function mount()
    {
        $this->assets = Asset::with('componentType')->get();
        $this->materials = LowValueMaterial::all();
        $this->dictAttributes = AttributeDictionary::orderBy('name')->get();
    }

    public function render()
    {
        $query = ItemProperty::with(['asset.componentType', 'nomenclature', 'attribute'])
            ->when($this->search, function($q) {
                $search = '%' . $this->search . '%';
                $q->where(function($sub) use ($search) {
                    $sub->where('attr_value', 'like', $search)
                        ->orWhereHas('attribute', function($attr) use ($search) {
                            $attr->where('name', 'like', $search);
                        })
                        ->orWhereHas('asset.componentType', function($ct) use ($search) {
                            $ct->where('component_name', 'like', $search);
                        })
                        ->orWhereHas('nomenclature', function($nom) use ($search) {
                            $nom->where('material_account_name', 'like', $search);
                        });
                });
            })
            ->when(!empty($this->filterAttribute), function($q) {
                $q->whereIn('attribute_id', $this->filterAttribute);
            })
            ->orderBy('id', 'desc');

        $this->properties = $query->get();
        return view('livewire.admin.item-property-manager');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterAttribute = [];
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
        $this->propertyId = null;
        $this->asset_id = null;
        $this->nomenclature_id = null;
        $this->attribute_id = null;
        $this->attr_value = '';
    }

    public function store()
    {
        $this->validate([
            'attribute_id' => 'required|exists:attributes_dictionary,id',
            'attr_value' => 'required|string|max:255',
            'asset_id' => 'nullable|exists:assets,id',
            'nomenclature_id' => 'nullable|exists:low_value_materials,id',
        ]);

        ItemProperty::updateOrCreate(['id' => $this->propertyId], [
            'asset_id' => $this->asset_id ?: null,
            'nomenclature_id' => $this->nomenclature_id ?: null,
            'attribute_id' => $this->attribute_id,
            'attr_value' => $this->attr_value,
        ]);

        session()->flash('message', 
            $this->propertyId ? 'Властивість оновлено.' : 'Властивість додано.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $prop = ItemProperty::findOrFail($id);
        $this->propertyId = $id;
        $this->asset_id = $prop->asset_id;
        $this->nomenclature_id = $prop->nomenclature_id;
        $this->attribute_id = $prop->attribute_id;
        $this->attr_value = $prop->attr_value;
        $this->openModal();
    }

    public function delete($id)
    {
        ItemProperty::findOrFail($id)->delete();
        session()->flash('message', 'Властивість видалено.');
    }
}
