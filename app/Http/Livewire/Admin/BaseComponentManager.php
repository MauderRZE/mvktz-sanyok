<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\BaseComponent;
use App\Livewire\Forms\BaseComponentForm;

#[Layout('layouts.admin')]
class BaseComponentManager extends Component
{
    public BaseComponentForm $form;
    
    public $components;
    public $isOpen = 0;

    public $search = '';
    public $filterCategory = [];

    public function render()
    {
        $query = BaseComponent::with('category')
            ->when($this->search, function($q) {
                $search = '%' . $this->search . '%';
                $q->where(function($sub) use ($search) {
                    $sub->where('component_name', 'like', $search)
                        ->orWhereHas('category', function($cat) use ($search) {
                            $cat->where('category_name', 'like', $search);
                        });
                });
            })
            ->when(!empty($this->filterCategory), function($q) {
                $q->whereIn('category_id', $this->filterCategory);
            })
            ->orderBy('id', 'desc');

        $this->components = $query->get();
        return view('livewire.admin.base-component-manager', [
            'categories' => \App\Models\EquipmentCategory::all(),
        ]);
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterCategory = [];
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
            $isUpdate ? 'Компонент оновлено.' : 'Компонент створено.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $comp = BaseComponent::findOrFail($id);
        $this->form->setComponent($comp);
        $this->openModal();
    }

    public function delete($id)
    {
        BaseComponent::find($id)->delete();
        session()->flash('message', 'Компонент видалено.');
    }
}
