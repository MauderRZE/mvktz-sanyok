<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Models\ItemProperty;

class ItemPropertyForm extends Form
{
    public ?int $propertyId = null;

    #[Validate('nullable|exists:assets,id')]
    public ?int $asset_id = null;

    #[Validate('nullable|exists:low_value_materials,id')]
    public ?int $nomenclature_id = null;

    #[Validate('required|exists:attributes_dictionary,id')]
    public ?int $attribute_id = null;

    #[Validate('required|string|max:255')]
    public string $attr_value = '';

    public function setProperty(ItemProperty $prop)
    {
        $this->propertyId = $prop->id;
        $this->asset_id = $prop->asset_id;
        $this->nomenclature_id = $prop->nomenclature_id;
        $this->attribute_id = $prop->attribute_id;
        $this->attr_value = $prop->attr_value;
    }

    public function store()
    {
        $this->validate();

        ItemProperty::updateOrCreate(['id' => $this->propertyId], [
            'asset_id' => $this->asset_id ?: null,
            'nomenclature_id' => $this->nomenclature_id ?: null,
            'attribute_id' => $this->attribute_id,
            'attr_value' => $this->attr_value,
        ]);

        $isUpdate = $this->propertyId !== null;
        $this->reset();
        
        return $isUpdate;
    }
}
