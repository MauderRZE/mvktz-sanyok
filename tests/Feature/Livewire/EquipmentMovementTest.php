<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\EquipmentMovementManager;
use App\Models\EquipmentMovement;
use App\Models\Asset;
use App\Models\Location;
use App\Models\Employee;
use App\Models\LocationHolder;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
            'position' => 'Тестер'
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
}
