<?php

namespace App\Http\Livewire\Admin;

use App\Livewire\Forms\EquipmentTypeForm;
use App\Models\BrandTz;
use App\Models\EquipmentType;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class TypeManager extends Component
{
    public EquipmentTypeForm $form;

    public $types;

    public $brands;

    public $isOpen = 0;

    public $search = '';

    public $filterBrand = [];

    public function render()
    {
        $query = EquipmentType::with('brand')
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
            ->orderBy('id', 'desc');

        $this->types = $query->get();
        $this->brands = BrandTz::all();

        return view('livewire.admin.type-manager');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterBrand = [];
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
