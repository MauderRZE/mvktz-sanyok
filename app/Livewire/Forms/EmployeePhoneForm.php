<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Models\EmployeePhone;

class EmployeePhoneForm extends Form
{
    public ?int $phoneId = null;

    #[Validate('required|exists:employee,id')]
    public ?int $employee_id = null;

    #[Validate('required|string|max:20')]
    public string $phone_number = '';

    #[Validate('required|string')]
    public string $phone_type = 'Робочий';

    public function setPhone(EmployeePhone $phone)
    {
        $this->phoneId = $phone->id;
        $this->employee_id = $phone->employee_id;
        $this->phone_number = $phone->phone_number;
        $this->phone_type = $phone->phone_type;
    }

    public function store()
    {
        $this->validate();

        EmployeePhone::updateOrCreate(['id' => $this->phoneId], [
            'employee_id' => $this->employee_id,
            'phone_number' => $this->phone_number,
            'phone_type' => $this->phone_type,
        ]);

        $isUpdate = $this->phoneId !== null;
        $this->reset();
        
        return $isUpdate;
    }
}
