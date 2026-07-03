<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\BrandTz;

class BrandManager extends Component
{
    public $brands, $brandId, $brandtz_name;
    public $isOpen = 0;

    public function render()
    {
        $this->brands = BrandTz::all();
        return view('livewire.admin.brand-manager')->layout('layouts.admin');
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
