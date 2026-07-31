<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Admin\Equipment\EquipmentMoveModal;
use App\Models\Asset;
use App\Models\BaseComponent;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\Location;
use App\Models\LocationHolder;
use App\Models\Organization;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class EquipmentMoveModalTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_move_asset_with_location_holder()
    {
        $equipment = Equipment::create([
            'inv_number' => 999901,
            'account_name' => 'PC Test',
            'status' => 'В експлуатації',
        ]);

        $baseComponent = BaseComponent::create([
            'component_name' => 'ОЗП Test',
        ]);

        $asset = Asset::create([
            'equipment_id' => $equipment->id,
            'base_component_id' => $baseComponent->id,
            'status' => 'працює',
            'serial_number' => 'SN_MOVE_TEST_1',
        ]);

        $location = Location::create([
            'room_number' => '101-Test',
        ]);

        $employee = Employee::create([
            'last_name' => 'Тестенко',
            'first_name' => 'Тест',
            'middle_name' => 'Тестович',
        ]);

        $organization = Organization::create([
            'org_name' => 'ТОВ Тестова Організація',
        ]);

        $holder = LocationHolder::create([
            'employee_id' => $employee->id,
            'organization_id' => $organization->id,
        ]);

        Livewire::test(EquipmentMoveModal::class)
            ->dispatch('openMoveAsset', id: $asset->id)
            ->assertSet('isOpen', true)
            ->set('location_id', $location->id)
            ->set('holder_id', $holder->id)
            ->call('store')
            ->assertHasNoErrors()
            ->assertSet('isOpen', false);

        $asset->refresh();
        $this->assertEquals($location->id, $asset->current_loc_id);
        $this->assertEquals($holder->id, $asset->current_holder_id);
        $this->assertNotNull($asset->holder);
        $this->assertEquals($employee->id, $asset->holder->employee_id);
        $this->assertEquals($organization->id, $asset->holder->organization_id);

        $this->assertDatabaseHas('movements', [
            'asset_id' => $asset->id,
            'location_id' => $location->id,
            'to_holder_id' => $holder->id,
        ]);
    }
}
