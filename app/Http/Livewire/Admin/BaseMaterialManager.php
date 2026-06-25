<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\BaseMaterial;

class BaseMaterialManager extends Component
{
    public $materials, $materialId, $material_name;
    public $isOpen = 0;

    public function render()
    {
        $this->materials = BaseMaterial::all();
        return view('livewire.admin.base-material-manager')->layout('layouts.admin');
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
        $this->materialId = null;
        $this->material_name = '';
    }

    public function store()
    {
        $this->validate([
            'material_name' => 'required|unique:base_materials,material_name,' . $this->materialId,
        ]);

        BaseMaterial::updateOrCreate(['id' => $this->materialId], [
            'material_name' => $this->material_name
        ]);

        session()->flash('message', 
            $this->materialId ? 'Матеріал оновлено.' : 'Матеріал створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $mat = BaseMaterial::findOrFail($id);
        $this->materialId = $id;
        $this->material_name = $mat->material_name;
        $this->openModal();
    }

    public function delete($id)
    {
        BaseMaterial::find($id)->delete();
        session()->flash('message', 'Матеріал видалено.');
    }
}
