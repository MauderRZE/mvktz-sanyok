<?php

namespace App\Http\Livewire\Admin;

use App\Livewire\Forms\ItemPropertyForm;
use App\Models\Asset;
use App\Models\AttributeDictionary;
use App\Models\ItemProperty;
use App\Models\LowValueMaterial;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class ItemPropertyManager extends Component
{
    public ItemPropertyForm $form;

    public $properties;

    public $assets;

    public $materials;

    public $dictAttributes;

    public $isOpen = false;

    public $search = '';

    public $filterAttribute = [];

    public $sortField = 'id';

    public $sortDirection = 'desc';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function mount()
    {
        $this->assets = Asset::with(['componentType', 'equipment'])->get();
        $this->materials = LowValueMaterial::all();
        $this->dictAttributes = AttributeDictionary::orderBy('name')->get();
    }

    public function render()
    {
        $query = ItemProperty::with(['asset.componentType', 'asset.equipment', 'nomenclature', 'attribute'])
            ->when($this->search, function ($q) {
                $search = '%'.$this->search.'%';
                $q->where(function ($sub) use ($search) {
                    $sub->where('attr_value', 'like', $search)
                        ->orWhereHas('attribute', function ($attr) use ($search) {
                            $attr->where('name', 'like', $search);
                        })
                        ->orWhereHas('asset.componentType', function ($ct) use ($search) {
                            $ct->where('component_name', 'like', $search);
                        })
                        ->orWhereHas('asset.equipment', function ($eq) use ($search) {
                            $eq->where('inv_number', 'like', $search);
                        })
                        ->orWhereHas('nomenclature', function ($nom) use ($search) {
                            $nom->where('material_account_name', 'like', $search);
                        });
                });
            })
            ->when(! empty($this->filterAttribute), function ($q) {
                $hasNull = in_array('null', $this->filterAttribute, true) || in_array(null, $this->filterAttribute, true);
                $ids = array_filter($this->filterAttribute, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($ids, $hasNull) {
                    if (! empty($ids)) {
                        $sub->whereIn('attribute_id', $ids);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('attribute_id');
                    }
                });
            });

        if ($this->sortField === 'inv_number') {
            $query->leftJoin('assets', 'item_properties.asset_id', '=', 'assets.id')
                ->leftJoin('equipment', 'assets.equipment_id', '=', 'equipment.id')
                ->select('item_properties.*')
                ->orderByRaw('CASE WHEN equipment.inv_number IS NULL OR equipment.inv_number = "" THEN 1 ELSE 0 END')
                ->orderBy('equipment.inv_number', $this->sortDirection);
        } else {
            $query->orderBy('item_properties.id', $this->sortDirection);
        }

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
        $this->form->reset();
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

    public function store()
    {
        $isUpdate = $this->form->store();

        session()->flash('message',
            $isUpdate ? 'Властивість оновлено.' : 'Властивість додано.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $prop = ItemProperty::findOrFail($id);
        $this->form->setProperty($prop);
        $this->openModal();
    }

    public function delete($id)
    {
        ItemProperty::findOrFail($id)->delete();
        session()->flash('message', 'Властивість видалено.');
    }
}
