<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\BrandTz;
use App\Livewire\Forms\BrandForm;

#[Layout('layouts.admin')]
class BrandManager extends Component
{
    public BrandForm $form;
    
    public $brands;
    public $isOpen = 0;

    public $search = '';

    public function render()
    {
        $this->brands = BrandTz::when($this->search, function($q) {
            $q->where('brandtz_name', 'like', '%' . $this->search . '%');
        })
        ->orderBy('id', 'desc')
        ->get();
        return view('livewire.admin.brand-manager');
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
            $isUpdate ? 'Бренд оновлено.' : 'Бренд створено.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $brand = BrandTz::findOrFail($id);
        $this->form->setBrand($brand);
        $this->openModal();
    }

    public function delete($id)
    {
        BrandTz::find($id)->delete();
        session()->flash('message', 'Бренд видалено.');
    }
}
