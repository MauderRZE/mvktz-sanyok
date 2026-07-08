<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\OrganizationManager;
use App\Models\Organization;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrganizationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(OrganizationManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_organization()
    {
        Livewire::test(OrganizationManager::class)
            ->call('create')
            ->set('form.org_name', 'Tech Corp')
            ->set('form.org_type', 'Стороння')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('organizations', [
            'org_name' => 'Tech Corp',
            'org_type' => 'Стороння',
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(OrganizationManager::class)
            ->call('create')
            ->set('form.org_name', '') // required
            ->call('store')
            ->assertHasErrors(['form.org_name']);
    }
}
