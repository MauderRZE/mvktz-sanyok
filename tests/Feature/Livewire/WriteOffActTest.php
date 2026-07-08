<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\WriteOffActManager;
use App\Models\LowValueWriteOffAct;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WriteOffActTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(WriteOffActManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_write_off_act()
    {
        Livewire::test(WriteOffActManager::class)
            ->call('create')
            ->set('form.act_number', 'WO-2023-01')
            ->set('form.act_date', '2023-11-05')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('low_value_write_off_acts', [
            'act_number' => 'WO-2023-01',
            'act_date' => '2023-11-05',
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(WriteOffActManager::class)
            ->call('create')
            ->set('form.act_number', '') // required
            ->set('form.act_date', '') // required
            ->call('store')
            ->assertHasErrors(['form.act_number', 'form.act_date']);
    }
}
