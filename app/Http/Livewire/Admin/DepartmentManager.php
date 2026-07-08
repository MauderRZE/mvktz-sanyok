<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Department;
use App\Livewire\Forms\DepartmentForm;

#[Layout('layouts.admin')]
class DepartmentManager extends Component
{
    public DepartmentForm $form;
    
    public $departments;
    public $isOpen = 0;

    public $search = '';

    public function render()
    {
        $this->departments = Department::when($this->search, function($q) {
            $q->where('name', 'like', '%' . $this->search . '%');
        })
        ->orderBy('id', 'desc')
        ->get();
        return view('livewire.admin.department-manager');
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
            $isUpdate ? 'Відділ оновлено.' : 'Відділ створено.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $dept = Department::findOrFail($id);
        $this->form->setDepartment($dept);
        $this->openModal();
    }

    public function delete($id)
    {
        Department::find($id)->delete();
        session()->flash('message', 'Відділ видалено.');
    }
}
