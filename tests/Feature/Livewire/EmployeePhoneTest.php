<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\EmployeePhoneManager;
use App\Models\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeePhoneTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(EmployeePhoneManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_employee_phone()
    {
        $employee = Employee::create([
            'last_name' => 'Іванов',
            'first_name' => 'Іван',
        ]);

        Livewire::test(EmployeePhoneManager::class)
            ->call('create')
            ->set('form.employee_id', $employee->id)
            ->set('form.phone_number', '+380501234567')
            ->set('form.phone_type', 'Робочий')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('employee_phones', [
            'employee_id' => $employee->id,
            'phone_number' => '+380501234567',
            'phone_type' => 'Робочий',
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(EmployeePhoneManager::class)
            ->call('create')
            ->set('form.phone_number', '') // required
            ->call('store')
            ->assertHasErrors(['form.employee_id', 'form.phone_number']);
    }
}
