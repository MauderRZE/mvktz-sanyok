<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\Equipment\EquipmentForm;
use App\Models\Equipment;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
            ->set('form.status', 'В експлуатації')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('equipment', [
            'inv_number' => 999002,
            'account_name' => 'Test Equipment',
            'status' => 'В експлуатації',
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
            'status' => 'В експлуатації',
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
}
