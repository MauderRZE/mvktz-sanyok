<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Employee;

#[Layout('layouts.admin')]
class EmployeeManager extends Component
{
    public $employees;
    public $employeeId;
    public $last_name, $first_name, $middle_name, $position, $department_id;
    public $departmentsList = [];
    public $isOpen = 0;

    public function render()
    {
        $this->employees = Employee::with('department')->get();
        $this->departmentsList = \App\Models\Department::all();
        return view('livewire.admin.employee-manager');
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
        $this->employeeId = null;
        $this->last_name = '';
        $this->first_name = '';
        $this->middle_name = '';
        $this->position = '';
        $this->department_id = null;
    }

    public function store()
    {
        $this->validate([
            'last_name' => 'required',
            'first_name' => 'required',
            'position' => 'nullable',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        Employee::updateOrCreate(['id' => $this->employeeId], [
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'position' => $this->position,
            'department_id' => $this->department_id
        ]);

        session()->flash('message', 
            $this->employeeId ? 'Співробітника оновлено успішно.' : 'Співробітника створено успішно.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $this->employeeId = $id;
        $this->last_name = $employee->last_name;
        $this->first_name = $employee->first_name;
        $this->middle_name = $employee->middle_name;
        $this->position = $employee->position;
        $this->department_id = $employee->department_id;
    
        $this->openModal();
    }

    public function delete($id)
    {
        Employee::find($id)->delete();
        session()->flash('message', 'Співробітника видалено успішно.');
    }
}
