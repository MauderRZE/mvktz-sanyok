<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\LocationManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LocationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(LocationManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_location()
    {
        Livewire::test(LocationManager::class)
            ->call('create')
            ->set('form.room_number', 'Кабінет 101')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('locations', [
            'room_number' => 'Кабінет 101',
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(LocationManager::class)
            ->call('create')
            ->set('form.room_number', '') // required
            ->call('store')
            ->assertHasErrors(['form.room_number']);
    }
}
