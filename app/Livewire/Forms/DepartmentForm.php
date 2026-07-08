<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Models\Department;

class DepartmentForm extends Form
{
    public ?int $departmentId = null;

    public string $name = '';

    public function setDepartment(Department $department)
    {
        $this->departmentId = $department->id;
        $this->name = $department->name;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:departments,name,' . $this->departmentId,
        ]);

        Department::updateOrCreate(['id' => $this->departmentId], [
            'name' => $this->name
        ]);

        $isUpdate = $this->departmentId !== null;
        $this->reset();
        
        return $isUpdate;
    }
}
