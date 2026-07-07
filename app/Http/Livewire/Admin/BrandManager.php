<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\BrandTz;

#[Layout('layouts.admin')]
class BrandManager extends Component
{
    public $brands, $brandId, $brandtz_name;
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
        $this->brandId = null;
        $this->brandtz_name = '';
    }

    public function store()
    {
        $this->validate([
            'brandtz_name' => 'required|unique:brands_tz,brandtz_name,' . $this->brandId,
        ]);

        BrandTz::updateOrCreate(['id' => $this->brandId], [
            'brandtz_name' => $this->brandtz_name
        ]);

        session()->flash('message', 
            $this->brandId ? 'Бренд оновлено.' : 'Бренд створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $brand = BrandTz::findOrFail($id);
        $this->brandId = $id;
        $this->brandtz_name = $brand->brandtz_name;
        $this->openModal();
    }

    public function delete($id)
    {
        BrandTz::find($id)->delete();
        session()->flash('message', 'Бренд видалено.');
    }
}
