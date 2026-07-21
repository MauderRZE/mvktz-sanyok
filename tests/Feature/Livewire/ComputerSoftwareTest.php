<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Admin\ComputerSoftwareManager;
use App\Models\Asset;
use App\Models\BaseComponent;
use App\Models\Equipment;
use App\Models\SoftwareLicense;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class ComputerSoftwareTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(ComputerSoftwareManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_computer_software()
    {
        $equipment = Equipment::create([
            'inv_number' => random_int(1000000, 9999999),
            'account_name' => 'PC',
            'status' => 'в експлуатації',
        ]);

        $baseComponent = BaseComponent::create([
            'component_name' => 'Системний блок',
        ]);

        $asset = Asset::create([
            'equipment_id' => $equipment->id,
            'base_component_id' => $baseComponent->id,
            'status' => 'Працює',
        ]);

        Livewire::test(ComputerSoftwareManager::class)
            ->call('create')
            ->set('form.computer_id', $asset->id)
            ->set('form.software_name', 'Windows')
            ->set('form.version', '11 Pro')
            ->set('form.is_licensed', true)
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('computer_software', [
            'computer_id' => $asset->id,
            'software_name' => 'Windows',
            'version' => '11 Pro',
            'is_licensed' => 1,
        ]);
    }

    public function test_can_associate_software_with_license()
    {
        $license = SoftwareLicense::create([
            'license_name' => 'Windows 11 Pro Key',
            'license_type' => 'OEM',
        ]);

        $equipment = Equipment::create([
            'inv_number' => random_int(1000000, 9999999),
            'account_name' => 'PC 2',
            'status' => 'в експлуатації',
        ]);

        $baseComponent = BaseComponent::create([
            'component_name' => 'Системний блок',
        ]);

        $asset = Asset::create([
            'equipment_id' => $equipment->id,
            'base_component_id' => $baseComponent->id,
            'status' => 'Працює',
        ]);

        Livewire::test(ComputerSoftwareManager::class)
            ->call('create')
            ->set('form.computer_id', $asset->id)
            ->set('form.software_name', 'Windows')
            ->set('form.version', '11 Pro')
            ->set('form.is_licensed', true)
            ->set('form.license_id', $license->id)
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('computer_software', [
            'computer_id' => $asset->id,
            'license_id' => $license->id,
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(ComputerSoftwareManager::class)
            ->call('create')
            ->set('form.software_name', '') // required
            ->call('store')
            ->assertHasErrors(['form.computer_id', 'form.software_name', 'form.version']);
    }
}
