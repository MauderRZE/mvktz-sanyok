<?php

namespace App\Livewire\Forms;

use App\Models\EquipmentType;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EquipmentTypeForm extends Form
{
    public ?int $typeId = null;

    #[Validate('nullable|exists:base_components,id')]
    public ?int $base_component_id = null;

    #[Validate('required')]
    public string $model_name = '';

    #[Validate('required|exists:brands_tz,id')]
    public ?int $brand_id = null;

    public function setType(EquipmentType $type)
    {
        $this->typeId = $type->id;
        $this->base_component_id = $type->base_component_id;
        $this->model_name = $type->model_name;
        $this->brand_id = $type->brand_id;
    }

    public function store()
    {
        $this->validate();

        $payload = [
            'model_name' => $this->model_name,
            'brand_id' => $this->brand_id,
        ];

        if (\Illuminate\Support\Facades\Schema::hasColumn('models_tz', 'base_component_id')) {
            $payload['base_component_id'] = $this->base_component_id;
        }

        EquipmentType::updateOrCreate(['id' => $this->typeId], $payload);

        $isUpdate = $this->typeId !== null;
        $this->reset();

        return $isUpdate;
    }
}
