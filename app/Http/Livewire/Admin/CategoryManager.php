<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\EquipmentCategory;

class CategoryManager extends Component
{
    public $categories, $categoryId, $category_name;
    public $isOpen = 0;

    public function render()
    {
        $this->categories = EquipmentCategory::all();
        return view('livewire.admin.category-manager')->layout('layouts.admin');
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
        $this->categoryId = null;
        $this->category_name = '';
    }

    public function store()
    {
        $this->validate([
            'category_name' => 'required',
        ]);

        EquipmentCategory::updateOrCreate(['id' => $this->categoryId], [
            'category_name' => $this->category_name
        ]);

        session()->flash('message', 
            $this->categoryId ? 'Категорію оновлено.' : 'Категорію створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $category = EquipmentCategory::findOrFail($id);
        $this->categoryId = $id;
        $this->category_name = $category->category_name;
        $this->openModal();
    }

    public function delete($id)
    {
        EquipmentCategory::find($id)->delete();
        session()->flash('message', 'Категорію видалено.');
    }
}
