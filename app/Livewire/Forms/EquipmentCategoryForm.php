<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Models\EquipmentCategory;

class EquipmentCategoryForm extends Form
{
    public ?int $categoryId = null;

    #[Validate('required')]
    public string $category_name = '';

    public function setCategory(EquipmentCategory $category)
    {
        $this->categoryId = $category->id;
        $this->category_name = $category->category_name;
    }

    public function store()
    {
        $this->validate();

        EquipmentCategory::updateOrCreate(['id' => $this->categoryId], [
            'category_name' => $this->category_name
        ]);

        $isUpdate = $this->categoryId !== null;
        $this->reset();
        
        return $isUpdate;
    }
}
