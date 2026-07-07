<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Department;

#[Layout('layouts.admin')]
class DepartmentManager extends Component
{
    public $departments, $departmentId, $name;
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
        $this->departmentId = null;
        $this->name = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:departments,name,' . $this->departmentId,
        ]);

        Department::updateOrCreate(['id' => $this->departmentId], [
            'name' => $this->name
        ]);

        session()->flash('message', 
            $this->departmentId ? 'Відділ оновлено.' : 'Відділ створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $dept = Department::findOrFail($id);
        $this->departmentId = $id;
        $this->name = $dept->name;
        $this->openModal();
    }

    public function delete($id)
    {
        Department::find($id)->delete();
        session()->flash('message', 'Відділ видалено.');
    }
}
