<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\EquipmentType;
use App\Models\BrandTz;

#[Layout('layouts.admin')]
class TypeManager extends Component
{
    public $types, $brands, $typeId, $model_name, $brand_id;
    public $isOpen = 0;

    public function render()
    {
        $this->types = EquipmentType::with('brand')->get();
        $this->brands = BrandTz::all();
        return view('livewire.admin.type-manager');
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
        $this->typeId = null;
        $this->model_name = '';
        $this->brand_id = null;
    }

    public function store()
    {
        $this->validate([
            'model_name' => 'required',
            'brand_id' => 'required',
        ]);

        EquipmentType::updateOrCreate(['id' => $this->typeId], [
            'model_name' => $this->model_name,
            'brand_id' => $this->brand_id,
        ]);

        session()->flash('message', 
            $this->typeId ? 'Модель оновлено.' : 'Модель створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $type = EquipmentType::findOrFail($id);
        $this->typeId = $id;
        $this->model_name = $type->model_name;
        $this->brand_id = $type->brand_id;
        $this->openModal();
    }

    public function delete($id)
    {
        EquipmentType::find($id)->delete();
        session()->flash('message', 'Модель видалено.');
    }
}
