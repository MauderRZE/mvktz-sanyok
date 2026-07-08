<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\EquipmentType;
use App\Models\BrandTz;
use App\Livewire\Forms\EquipmentTypeForm;

#[Layout('layouts.admin')]
class TypeManager extends Component
{
    public EquipmentTypeForm $form;
    
    public $types, $brands;
    public $isOpen = 0;

    public $search = '';
    public $filterBrand = [];

    public function render()
    {
        $query = EquipmentType::with('brand')
            ->when($this->search, function($q) {
                $search = '%' . $this->search . '%';
                $q->where(function($sub) use ($search) {
                    $sub->where('model_name', 'like', $search)
                        ->orWhereHas('brand', function($b) use ($search) {
                            $b->where('brandtz_name', 'like', $search);
                        });
                });
            })
            ->when(!empty($this->filterBrand), function($q) {
                $q->whereIn('brand_id', $this->filterBrand);
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
