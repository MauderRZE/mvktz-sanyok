<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\BaseComponent;

class BaseComponentManager extends Component
{
    public $components, $componentId, $component_name;
    public $isOpen = 0;

    public function render()
    {
        $this->components = BaseComponent::all();
        return view('livewire.admin.base-component-manager')->layout('layouts.admin');
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
    }

    public function store()
    {
        $this->validate([
            'component_name' => 'required|unique:base_components,component_name,' . $this->componentId,
        ]);

        BaseComponent::updateOrCreate(['id' => $this->componentId], [
            'component_name' => $this->component_name
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
        $this->openModal();
    }

    public function delete($id)
    {
        BaseComponent::find($id)->delete();
        session()->flash('message', 'Компонент видалено.');
    }
}
