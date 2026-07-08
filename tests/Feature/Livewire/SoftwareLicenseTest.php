<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\SoftwareLicenseManager;
use App\Models\SoftwareLicense;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SoftwareLicenseTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(SoftwareLicenseManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_software_license()
    {
        Livewire::test(SoftwareLicenseManager::class)
            ->call('create')
            ->set('form.license_name', 'Windows 11 Pro_Test_999')
            ->set('form.license_type', 'OEM')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('licenses', [
            'license_name' => 'Windows 11 Pro_Test_999',
            'license_type' => 'OEM',
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(SoftwareLicenseManager::class)
            ->call('create')
            ->set('form.license_name', '') // required
            ->call('store')
            ->assertHasErrors(['form.license_name']);
    }
}
