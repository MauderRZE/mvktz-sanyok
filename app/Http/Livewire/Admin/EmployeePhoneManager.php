<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\EmployeePhone;
use App\Models\Employee;

#[Layout('layouts.admin')]
class EmployeePhoneManager extends Component
{
    public $phones, $phoneId, $employee_id, $phone_number, $phone_type;
    public $employees;
    public $isOpen = false;

    public function mount()
    {
        $this->employees = Employee::orderBy('last_name')->get();
    }

    public function render()
    {
        $this->phones = EmployeePhone::with('employee')->get();
        return view('livewire.admin.employee-phone-manager');
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

    private function resetInputFields()
    {
        $this->phoneId = null;
        $this->employee_id = null;
        $this->phone_number = '';
        $this->phone_type = 'Робочий';
    }

    public function store()
    {
        $this->validate([
            'employee_id' => 'required|exists:employee,id',
            'phone_number' => 'required|string|max:20',
            'phone_type' => 'required|string',
        ]);

        EmployeePhone::updateOrCreate(['id' => $this->phoneId], [
            'employee_id' => $this->employee_id,
            'phone_number' => $this->phone_number,
            'phone_type' => $this->phone_type,
        ]);

        session()->flash('message', 
            $this->phoneId ? 'Телефон оновлено.' : 'Телефон додано.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $phone = EmployeePhone::findOrFail($id);
        $this->phoneId = $id;
        $this->employee_id = $phone->employee_id;
        $this->phone_number = $phone->phone_number;
        $this->phone_type = $phone->phone_type;
        $this->openModal();
    }

    public function delete($id)
    {
        EmployeePhone::findOrFail($id)->delete();
        session()->flash('message', 'Телефон видалено.');
    }
}
