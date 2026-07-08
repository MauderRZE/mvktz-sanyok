<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\DepartmentManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DepartmentTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(DepartmentManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_department()
    {
        Livewire::test(DepartmentManager::class)
            ->call('create')
            ->set('form.name', 'IT Відділ')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('departments', [
            'name' => 'IT Відділ',
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(DepartmentManager::class)
            ->call('create')
            ->set('form.name', '') // required
            ->call('store')
            ->assertHasErrors(['form.name']);
    }
}
