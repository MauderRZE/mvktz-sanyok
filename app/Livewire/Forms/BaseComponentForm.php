<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Models\BaseComponent;

class BaseComponentForm extends Form
{
    public ?int $componentId = null;

    public string $component_name = '';

    #[Validate('nullable|exists:categories_tz,id')]
    public ?int $category_id = null;

    public function setComponent(BaseComponent $comp)
    {
        $this->componentId = $comp->id;
        $this->component_name = $comp->component_name;
        $this->category_id = $comp->category_id;
    }

    public function store()
    {
        $this->validate([
            'component_name' => 'required|unique:base_components,component_name,' . $this->componentId,
        ]);

        BaseComponent::updateOrCreate(['id' => $this->componentId], [
            'component_name' => $this->component_name,
            'category_id' => $this->category_id ?: null,
        ]);

        $isUpdate = $this->componentId !== null;
        $this->reset();
        
        return $isUpdate;
    }
}
