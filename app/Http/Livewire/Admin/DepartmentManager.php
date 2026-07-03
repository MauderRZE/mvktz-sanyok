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

    public function render()
    {
        $this->departments = Department::all();
        return view('livewire.admin.department-manager');
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
