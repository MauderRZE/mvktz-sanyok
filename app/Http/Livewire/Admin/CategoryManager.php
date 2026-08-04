<?php

namespace App\Http\Livewire\Admin;

use App\Livewire\Forms\EquipmentCategoryForm;
use App\Models\EquipmentCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class CategoryManager extends Component
{
    use WithPagination;

    public EquipmentCategoryForm $form;

    public $isOpen = 0;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = EquipmentCategory::when($this->search, function ($q) {
            $q->where('category_name', 'like', '%'.$this->search.'%');
        })
            ->orderBy('id', 'desc');

        return view('livewire.admin.category-manager', [
            'categories' => $query->paginate(15),
        ]);
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->resetPage();
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
