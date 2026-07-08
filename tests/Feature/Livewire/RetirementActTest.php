<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\RetirementActManager;
use App\Models\EquipmentRetirementAct;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RetirementActTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(RetirementActManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_retirement_act()
    {
        Livewire::test(RetirementActManager::class)
            ->call('create')
            ->set('form.act_number', 'ACT-2023-01')
            ->set('form.act_date', '2023-11-01')
            ->set('form.reason', 'Зламано')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('equipment_retirement_acts', [
            'act_number' => 'ACT-2023-01',
            'act_date' => '2023-11-01',
            'reason' => 'Зламано',
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(RetirementActManager::class)
            ->call('create')
            ->set('form.act_number', '') // required
            ->set('form.act_date', '') // required
            ->call('store')
            ->assertHasErrors(['form.act_number', 'form.act_date']);
    }
}
