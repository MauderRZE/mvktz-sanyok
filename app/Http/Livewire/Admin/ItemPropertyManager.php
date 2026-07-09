<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\ItemProperty;
use App\Models\Asset;
use App\Models\LowValueMaterial;
use App\Models\AttributeDictionary;
use App\Livewire\Forms\ItemPropertyForm;

#[Layout('layouts.admin')]
class ItemPropertyManager extends Component
{
    public ItemPropertyForm $form;
    
    public $properties;
    public $assets, $materials, $dictAttributes;
    public $isOpen = false;

    public $search = '';
    public $filterAttribute = [];

    public function mount()
    {
        $this->assets = Asset::with(['componentType', 'equipment'])->get();
        $this->materials = LowValueMaterial::all();
        $this->dictAttributes = AttributeDictionary::orderBy('name')->get();
    }

    public function render()
    {
        $query = ItemProperty::with(['asset.componentType', 'asset.equipment', 'nomenclature', 'attribute'])
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
