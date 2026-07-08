<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\EmployeeManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(EmployeeManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_employee()
    {
        Livewire::test(EmployeeManager::class)
            ->call('create')
            ->set('form.last_name', 'Іванов')
            ->set('form.first_name', 'Іван')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('employee', [
            'last_name' => 'Іванов',
            'first_name' => 'Іван',
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(EmployeeManager::class)
            ->call('create')
            ->set('form.last_name', '') // required
            ->set('form.first_name', '') // required
            ->call('store')
            ->assertHasErrors(['form.last_name', 'form.first_name']);
    }
}
