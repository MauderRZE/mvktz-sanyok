<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Admin\Equipment\EquipmentForm;
use App\Models\Equipment;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class EquipmentFormTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(EquipmentForm::class)
            ->call('create')
            ->assertStatus(200);
    }

    public function test_can_create_equipment()
    {
        Livewire::test(EquipmentForm::class)
            ->call('create')
            ->set('form.inv_number', 999002)
            ->set('form.account_name', 'Test Equipment')
            ->set('form.status', 'в експлуатації')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('equipment', [
            'inv_number' => 999002,
            'account_name' => 'Test Equipment',
            'status' => 'в експлуатації',
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(EquipmentForm::class)
            ->call('create')
            ->set('form.inv_number', '') // required
            ->set('form.account_name', '') // required
            ->call('store')
            ->assertHasErrors(['form.inv_number', 'form.account_name']);
    }

    public function test_can_edit_equipment()
    {
        $equipment = Equipment::create([
            'inv_number' => 999002,
            'account_name' => 'Test Equipment',
            'status' => 'в експлуатації',
        ]);

        Livewire::test(EquipmentForm::class)
            ->call('edit', $equipment->id)
            ->assertSet('form.inv_number', 999002)
            ->assertSet('form.account_name', 'Test Equipment')
            ->set('form.account_name', 'Test Equipment Updated')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('equipment', [
            'id' => $equipment->id,
            'account_name' => 'Test Equipment Updated',
        ]);
    }

    public function test_can_set_equipment_status_to_on_warehouse()
    {
        $equipment = Equipment::create([
            'inv_number' => 999003,
            'account_name' => 'Warehouse Equipment',
            'status' => 'в експлуатації',
        ]);

        Livewire::test(EquipmentForm::class)
            ->call('edit', $equipment->id)
            ->set('form.status', 'на списання')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('equipment', [
            'id' => $equipment->id,
            'status' => 'на списання',
        ]);
    }

    public function test_can_create_equipment_with_string_inv_number()
    {
        Livewire::test(EquipmentForm::class)
            ->call('create')
            ->set('form.inv_number', 'БН')
            ->set('form.account_name', 'Склад')
            ->set('form.status', 'в експлуатації')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('equipment', [
            'inv_number' => 'БН',
            'account_name' => 'Склад',
            'status' => 'в експлуатації',
        ]);
    }
}
