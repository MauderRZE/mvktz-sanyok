<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Admin\EquipmentMovementManager;
use App\Models\Asset;
use App\Models\Employee;
use App\Models\Location;
use App\Models\LocationHolder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class EquipmentMovementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(EquipmentMovementManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_movement()
    {
        // Враховуємо, що Asset може вимагати прив'язки до Equipment, BaseComponent, тощо
        // Але оскільки фабрики не гарантовані, спробуємо створити базові моделі вручну
        $location = Location::create(['room_number' => '101A']);
        $employee = Employee::create([
            'first_name' => 'Іван',
            'last_name' => 'Іванов',
            'position' => 'Тестер',
        ]);

        $asset = Asset::create([
            'status' => 'Працює',
            'current_loc_id' => null,
            'current_holder_id' => null,
        ]);

        Livewire::test(EquipmentMovementManager::class)
            ->call('create')
            ->set('form.asset_id', $asset->id)
            ->set('form.location_id', $location->id)
            ->set('form.employee_id', $employee->id)
            ->set('form.action_date', '2023-10-10')
            ->call('store')
            ->assertHasNoErrors();

        // Перевіряємо чи створився запис про переміщення
        $this->assertDatabaseHas('movements', [ // Припускаємо що таблиця називається movements або equipment_movements
            'asset_id' => $asset->id,
            'employee_id' => $employee->id,
            'action_date' => '2023-10-10',
        ]);

        // Перевіряємо чи оновився актив
        $asset->refresh();
        $this->assertEquals($location->id, $asset->current_loc_id);

        $holder = LocationHolder::find($asset->current_holder_id);
        $this->assertNotNull($holder);
        $this->assertEquals($employee->id, $holder->employee_id);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(EquipmentMovementManager::class)
            ->call('create')
            ->set('form.action_date', '')
            ->call('store')
            ->assertHasErrors(['form.asset_id', 'form.location_id', 'form.action_date']);
    }

    public function test_multiple_movements_preserve_location_history()
    {
        $loc1 = Location::create(['room_number' => '101']);
        $loc2 = Location::create(['room_number' => '202']);

        $asset = Asset::create([
            'status' => 'Працює',
            'current_loc_id' => null,
            'current_holder_id' => null,
        ]);

        // First move to 101
        Livewire::test(EquipmentMovementManager::class)
            ->call('create')
            ->set('form.asset_id', $asset->id)
            ->set('form.location_id', $loc1->id)
            ->set('form.action_date', '2026-01-01')
            ->call('store')
            ->assertHasNoErrors();

        // Second move to 202
        Livewire::test(EquipmentMovementManager::class)
            ->call('create')
            ->set('form.asset_id', $asset->id)
            ->set('form.location_id', $loc2->id)
            ->set('form.action_date', '2026-02-01')
            ->call('store')
            ->assertHasNoErrors();

        // Assert movement history records maintain distinct location_id values
        $this->assertDatabaseHas('movements', [
            'asset_id' => $asset->id,
            'location_id' => $loc1->id,
            'action_date' => '2026-01-01 00:00:00',
        ]);

        $this->assertDatabaseHas('movements', [
            'asset_id' => $asset->id,
            'location_id' => $loc2->id,
            'action_date' => '2026-02-01 00:00:00',
        ]);

        // Asset current location is loc2
        $asset->refresh();
        $this->assertEquals($loc2->id, $asset->current_loc_id);
    }
}
