<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Models\EquipmentType;

class EquipmentTypeForm extends Form
{
    public ?int $typeId = null;

    #[Validate('required')]
    public string $model_name = '';

    #[Validate('required|exists:brands_tz,id')]
    public ?int $brand_id = null;

    public function setType(EquipmentType $type)
    {
        $this->typeId = $type->id;
        $this->model_name = $type->model_name;
        $this->brand_id = $type->brand_id;
    }

    public function store()
    {
        $this->validate();

        EquipmentType::updateOrCreate(['id' => $this->typeId], [
            'model_name' => $this->model_name,
            'brand_id' => $this->brand_id,
        ]);

        $isUpdate = $this->typeId !== null;
        $this->reset();
        
        return $isUpdate;
    }
}
