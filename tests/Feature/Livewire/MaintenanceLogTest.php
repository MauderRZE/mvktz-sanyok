<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\MaintenanceLogManager;
use App\Models\Asset;
use App\Models\Equipment;
use App\Models\BaseComponent;
use App\Models\MaintenanceLog;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MaintenanceLogTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(MaintenanceLogManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_maintenance_log()
    {
        $equipment = Equipment::create([
            'inv_number' => 999003,
            'account_name' => 'PC',
            'status' => 'В експлуатації'
        ]);

        $baseComponent = BaseComponent::create([
            'component_name' => 'Монітор'
        ]);

        $asset = Asset::create([
            'equipment_id' => $equipment->id,
            'base_component_id' => $baseComponent->id,
            'status' => 'Працює',
        ]);

        Livewire::test(MaintenanceLogManager::class)
            ->call('create')
            ->set('form.assets_id', $asset->id)
            ->set('form.sent_date', '2026-07-08')
            ->set('form.issue_description', 'Не вмикається')
            ->set('form.status', 'В ремонті')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('repairs', [
            'assets_id' => $asset->id,
            'sent_date' => '2026-07-08',
            'issue_description' => 'Не вмикається',
            'status' => 'В ремонті',
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(MaintenanceLogManager::class)
            ->call('create')
            ->set('form.issue_description', '') // required
            ->call('store')
            ->assertHasErrors(['form.assets_id', 'form.issue_description']);
    }
}
