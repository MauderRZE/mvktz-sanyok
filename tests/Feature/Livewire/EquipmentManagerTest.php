<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\EquipmentManager;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EquipmentManagerTest extends TestCase
{
    use DatabaseTransactions;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::firstOrCreate(
            ['login' => 'admin'],
            ['name' => 'admin', 'password' => bcrypt('$B00ster!')]
        );
    }

    public function test_renders_successfully()
    {
        $this->actingAs($this->user);
        Livewire::test(EquipmentManager::class)
            ->assertStatus(200);
    }

    public function test_can_delete_equipment()
    {
        $this->actingAs($this->user);
        
        $equipment = Equipment::create([
            'inv_number' => 999999,
            'account_name' => 'PC to Delete',
            'status' => 'В експлуатації'
        ]);

        Livewire::test(EquipmentManager::class)
            ->call('delete', $equipment->id);

        $this->assertDatabaseMissing('equipment', [
            'id' => $equipment->id,
        ]);
    }

    public function test_can_search_equipment()
    {
        $this->actingAs($this->user);

        $eq1 = Equipment::create([
            'inv_number' => 999991,
            'account_name' => 'UniqueSearchItemName',
            'status' => 'В експлуатації'
        ]);

        $eq2 = Equipment::create([
            'inv_number' => 999992,
            'account_name' => 'OtherItemName',
            'status' => 'В експлуатації'
        ]);

        Livewire::test(EquipmentManager::class)
            ->set('search', 'UniqueSearchItemName')
            ->assertSee('UniqueSearchItemName')
            ->assertDontSee('OtherItemName');
    }

    public function test_can_filter_equipment_by_status()
    {
        $this->actingAs($this->user);

        $eq1 = Equipment::create([
            'inv_number' => 999993,
            'account_name' => 'FilterItem1',
            'status' => 'В експлуатації'
        ]);

        $eq2 = Equipment::create([
            'inv_number' => 999994,
            'account_name' => 'FilterItem2',
            'status' => 'Списано'
        ]);

        Livewire::test(EquipmentManager::class)
            ->set('filterStatus', ['Списано'])
            ->assertSee('FilterItem2')
            ->assertDontSee('FilterItem1');
    }

    public function test_equipment_lifecycle_crud()
    {
        $this->actingAs($this->user);

        // 1. Створення (Create)
        Livewire::test(\App\Http\Livewire\Admin\Equipment\EquipmentForm::class)
            ->call('create')
            ->set('form.inv_number', 999002)
            ->set('form.account_name', 'Test Equipment')
            ->set('form.status', 'В експлуатації')
            ->call('store')
            ->assertHasNoErrors();

        $equipment = Equipment::where('inv_number', 999002)->first();
        $this->assertNotNull($equipment);
        $this->assertEquals('Test Equipment', $equipment->account_name);

        // 2. Редагування (Update)
        Livewire::test(\App\Http\Livewire\Admin\Equipment\EquipmentForm::class)
            ->call('edit', $equipment->id)
            ->assertSet('form.account_name', 'Test Equipment')
            ->set('form.account_name', 'Test Equipment Updated')
            ->call('store')
            ->assertHasNoErrors();

        $equipment->refresh();
        $this->assertEquals('Test Equipment Updated', $equipment->account_name);

        Livewire::test(EquipmentManager::class)
            ->call('delete', $equipment->id);

        $this->assertDatabaseMissing('equipment', [
            'id' => $equipment->id,
        ]);
    }

    public function test_equipment_detail_opens_with_data()
    {
        $this->actingAs($this->user);

        $equipment = Equipment::create([
            'inv_number' => 999002,
            'account_name' => 'Test Equipment',
            'status' => 'В експлуатації'
        ]);

        Livewire::test(\App\Http\Livewire\Admin\Equipment\EquipmentDetail::class)
            ->call('open', $equipment->id)
            ->assertSet('isOpen', true)
            ->assertSet('equipmentId', $equipment->id)
            ->assertSet('equipment.account_name', 'Test Equipment');
    }
}
