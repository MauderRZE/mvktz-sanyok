<?php

namespace App\Http\Livewire\Admin;

use App\Livewire\Forms\EquipmentTypeForm;
use App\Models\BaseComponent;
use App\Models\BrandTz;
use App\Models\EquipmentType;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class TypeManager extends Component
{
    use WithPagination;

    public EquipmentTypeForm $form;

    public $isOpen = 0;

    public $search = '';

    public $filterBrand = [];

    public $base_component_id;

    public $filterBaseComponent = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterBrand()
    {
        $this->resetPage();
    }

    public function updatingFilterBaseComponent()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = EquipmentType::with(['brand', 'baseComponent'])
            ->when($this->search, function ($q) {
                $search = '%'.$this->search.'%';
                $q->where(function ($sub) use ($search) {
                    $sub->where('model_name', 'like', $search)
                        ->orWhereHas('brand', function ($b) use ($search) {
                            $b->where('brandtz_name', 'like', $search);
                        });
                });
            })
            ->when(! empty($this->filterBrand), function ($q) {
                $hasNull = in_array('null', $this->filterBrand, true) || in_array(null, $this->filterBrand, true);
                $ids = array_filter($this->filterBrand, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($ids, $hasNull) {
                    if (! empty($ids)) {
                        $sub->whereIn('brand_id', $ids);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('brand_id');
                    }
                });
            })
            ->when(! empty($this->filterBaseComponent), function ($q) {
                $hasNull = in_array('null', (array) $this->filterBaseComponent, true) || in_array(null, (array) $this->filterBaseComponent, true);
                $ids = array_filter((array) $this->filterBaseComponent, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($ids, $hasNull) {
                    if (! empty($ids)) {
                        $sub->whereIn('base_component_id', $ids);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('base_component_id');
                    }
                });
            })
            ->orderBy('id', 'desc');

        return view('livewire.admin.type-manager', [
            'types' => $query->paginate(15),
            'baseComponents' => BaseComponent::orderBy('component_name')->get(),
            'brands' => BrandTz::orderBy('brandtz_name')->get(),
        ]);
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterBrand = [];
        $this->filterBaseComponent = [];
        $this->resetPage();
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
            $isUpdate ? 'Модель оновлено.' : 'Модель створено.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $type = EquipmentType::findOrFail($id);
        $this->form->setType($type);
        $this->openModal();
    }

    public function delete($id)
    {
        EquipmentType::find($id)->delete();
        session()->flash('message', 'Модель видалено.');
    }
}
