<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Models\Employee;

class EmployeeForm extends Form
{
    public ?int $employeeId = null;

    #[Validate('required')]
    public string $last_name = '';

    #[Validate('required')]
    public string $first_name = '';

    public ?string $middle_name = '';

    public ?string $position = '';

    #[Validate('nullable|exists:departments,id')]
    public ?int $department_id = null;

    public function setEmployee(Employee $employee)
    {
        $this->employeeId = $employee->id;
        $this->last_name = $employee->last_name;
        $this->first_name = $employee->first_name;
        $this->middle_name = $employee->middle_name;
        $this->position = $employee->position;
        $this->department_id = $employee->department_id;
    }

    public function store()
    {
        $this->validate();

        Employee::updateOrCreate(['id' => $this->employeeId], [
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'position' => $this->position,
            'department_id' => $this->department_id
        ]);

        $isUpdate = $this->employeeId !== null;
        $this->reset();
        
        return $isUpdate;
    }
}
