<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Models\AttributeDictionary;

class AttributeDictionaryForm extends Form
{
    public ?int $attributeId = null;

    #[Validate('required|string|max:100')]
    public string $name = '';

    public function setAttribute(AttributeDictionary $attribute)
    {
        $this->attributeId = $attribute->id;
        $this->name = $attribute->name;
    }

    public function store()
    {
        $this->validate();

        AttributeDictionary::updateOrCreate(['id' => $this->attributeId], [
            'name' => $this->name
        ]);

        $isUpdate = $this->attributeId !== null;
        $this->reset();
        
        return $isUpdate;
    }
}
