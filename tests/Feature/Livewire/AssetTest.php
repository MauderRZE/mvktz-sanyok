<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\AssetManager;
use App\Models\Asset;
use App\Models\Equipment;
use App\Models\BaseComponent;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AssetTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(AssetManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_asset()
    {
        $equipment = Equipment::create([
            'inv_number' => 999004,
            'account_name' => 'PC',
            'status' => 'В експлуатації'
        ]);

        $baseComponent = BaseComponent::create([
            'component_name' => 'Системний блок'
        ]);

        Livewire::test(AssetManager::class)
            ->call('create')
            ->set('form.equipment_id', $equipment->id)
            ->set('form.base_component_id', $baseComponent->id)
            ->set('form.status', 'Працює')
            ->set('form.serial_number', 'SN123456')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('assets', [
            'equipment_id' => $equipment->id,
            'base_component_id' => $baseComponent->id,
            'status' => 'Працює',
            'serial_number' => 'SN123456'
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(AssetManager::class)
            ->call('create')
            ->call('store')
            ->assertHasErrors(['form.equipment_id', 'form.base_component_id']);
    }
}
