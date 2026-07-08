<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\ItemPropertyManager;
use App\Models\AttributeDictionary;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ItemPropertyTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(ItemPropertyManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_item_property()
    {
        $attribute = AttributeDictionary::create([
            'name' => 'Колір'
        ]);

        Livewire::test(ItemPropertyManager::class)
            ->call('create')
            ->set('form.attribute_id', $attribute->id)
            ->set('form.attr_value', 'Чорний')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('item_properties', [
            'attribute_id' => $attribute->id,
            'attr_value' => 'Чорний',
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(ItemPropertyManager::class)
            ->call('create')
            ->set('form.attr_value', '') // required
            ->call('store')
            ->assertHasErrors(['form.attribute_id', 'form.attr_value']);
    }
}
