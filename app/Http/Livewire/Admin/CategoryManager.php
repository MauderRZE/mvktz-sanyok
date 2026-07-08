<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\EquipmentCategory;
use App\Livewire\Forms\EquipmentCategoryForm;

#[Layout('layouts.admin')]
class CategoryManager extends Component
{
    public EquipmentCategoryForm $form;
    
    public $categories;
    public $isOpen = 0;

    public $search = '';

    public function render()
    {
        $this->categories = EquipmentCategory::when($this->search, function($q) {
            $q->where('category_name', 'like', '%' . $this->search . '%');
        })
        ->orderBy('id', 'desc')
        ->get();
        return view('livewire.admin.category-manager');
    }

    public function resetFilters()
    {
        $this->search = '';
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
            $isUpdate ? 'Категорію оновлено.' : 'Категорію створено.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $category = EquipmentCategory::findOrFail($id);
        $this->form->setCategory($category);
        $this->openModal();
    }

    public function delete($id)
    {
        EquipmentCategory::find($id)->delete();
        session()->flash('message', 'Категорію видалено.');
    }
}
