<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\AttributeDictionaryManager;
use App\Models\AttributeDictionary;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AttributeDictionaryTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(AttributeDictionaryManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_attribute()
    {
        Livewire::test(AttributeDictionaryManager::class)
            ->call('create')
            ->set('form.name', 'Об\'єм пам\'яті')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('attributes_dictionary', [
            'name' => 'Об\'єм пам\'яті',
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(AttributeDictionaryManager::class)
            ->call('create')
            ->set('form.name', '') // required
            ->call('store')
            ->assertHasErrors(['form.name']);
    }
}
