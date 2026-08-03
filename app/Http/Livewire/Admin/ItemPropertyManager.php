<?php

namespace App\Http\Livewire\Admin;

use App\Livewire\Forms\ItemPropertyForm;
use App\Models\Asset;
use App\Models\AttributeDictionary;
use App\Models\ItemProperty;
use App\Models\LowValueMaterial;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ItemPropertyManager extends Component
{
    use WithPagination;

    public ItemPropertyForm $form;

    public bool $isOpen = false;

    #[Url(history: true)]
    public string $search = '';

    public array $filterAttribute = [];

    public string $sortField = 'id';

    public string $sortDirection = 'desc';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterAttribute(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = ItemProperty::with(['asset.componentType', 'asset.equipment', 'nomenclature', 'attribute'])
            ->when($this->search, function ($q) {
                $search = '%'.$this->search.'%';
                $q->where(function ($sub) use ($search) {
                    $sub->where('attr_value', 'like', $search)
                        ->orWhereHas('attribute', fn ($attr) => $attr->where('name', 'like', $search))
                        ->orWhereHas('asset.componentType', fn ($ct) => $ct->where('component_name', 'like', $search))
                        ->orWhereHas('asset.equipment', fn ($eq) => $eq->where('inv_number', 'like', $search))
                        ->orWhereHas('nomenclature', fn ($nom) => $nom->where('material_account_name', 'like', $search));
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

        return view('livewire.admin.item-property-manager', [
            'properties' => $query->paginate(25),
            'assets' => Asset::with(['componentType', 'equipment'])->get(),
            'materials' => LowValueMaterial::all(),
            'dictAttributes' => AttributeDictionary::orderBy('name')->get(),
        ]);
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'filterAttribute']);
        $this->resetPage();
    }

    public function create(): void
    {
        $this->form->reset();
        $this->openModal();
    }

    public function openModal(): void
    {
        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->form->reset();
    }

    public function store(): void
    {
        $isUpdate = $this->form->store();

        session()->flash('message', $isUpdate ? 'Властивість оновлено.' : 'Властивість додано.');

        $this->closeModal();
    }

    public function edit(int $id): void
    {
        $prop = ItemProperty::findOrFail($id);
        $this->form->setProperty($prop);
        $this->openModal();
    }

    public function delete(int $id): void
    {
        ItemProperty::findOrFail($id)->delete();
        session()->flash('message', 'Властивість видалено.');
    }
}
