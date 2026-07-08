<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\ComputerSoftwareManager;
use App\Models\Asset;
use App\Models\Equipment;
use App\Models\BaseComponent;
use App\Models\ComputerSoftware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
            'inv_number' => 999001,
            'account_name' => 'PC',
            'status' => 'В експлуатації'
        ]);

        $baseComponent = BaseComponent::create([
            'component_name' => 'Системний блок'
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

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(ComputerSoftwareManager::class)
            ->call('create')
            ->set('form.software_name', '') // required
            ->call('store')
            ->assertHasErrors(['form.computer_id', 'form.software_name', 'form.version']);
    }
}
