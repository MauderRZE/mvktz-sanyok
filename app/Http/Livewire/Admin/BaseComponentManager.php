<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\BaseComponent;

#[Layout('layouts.admin')]
class BaseComponentManager extends Component
{
    public $components, $componentId, $component_name, $category_id;
    public $isOpen = 0;

    public function render()
    {
        $this->components = BaseComponent::with('category')->get();
        return view('livewire.admin.base-component-manager', [
            'categories' => \App\Models\EquipmentCategory::all(),
        ]);
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
        $this->componentId = null;
        $this->component_name = '';
        $this->category_id = '';
    }

    public function store()
    {
        $this->validate([
            'component_name' => 'required|unique:base_components,component_name,' . $this->componentId,
            'category_id' => 'nullable|exists:categories_tz,id',
        ]);

        BaseComponent::updateOrCreate(['id' => $this->componentId], [
            'component_name' => $this->component_name,
            'category_id' => $this->category_id ?: null,
        ]);

        session()->flash('message', 
            $this->componentId ? 'Компонент оновлено.' : 'Компонент створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $comp = BaseComponent::findOrFail($id);
        $this->componentId = $id;
        $this->component_name = $comp->component_name;
        $this->category_id = $comp->category_id;
        $this->openModal();
    }

    public function delete($id)
    {
        BaseComponent::find($id)->delete();
        session()->flash('message', 'Компонент видалено.');
    }
}
