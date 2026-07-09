<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\ItemPropertyManager;
use App\Models\AttributeDictionary;
use App\Models\ItemProperty;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ItemPropertyTest extends TestCase
{
    use DatabaseTransactions;

    private AttributeDictionary $attribute;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->attribute = AttributeDictionary::create([
            'name' => 'Колір'
        ]);
    }

    public function test_renders_successfully()
    {
        Livewire::test(ItemPropertyManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_item_property()
    {
        Livewire::test(ItemPropertyManager::class)
            ->call('create')
            ->set('form.attribute_id', $this->attribute->id)
            ->set('form.attr_value', 'Чорний')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('item_properties', [
            'attribute_id' => $this->attribute->id,
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

    public function test_can_edit_item_property()
    {
        $property = ItemProperty::create([
            'attribute_id' => $this->attribute->id,
            'attr_value' => 'Чорний'
        ]);

        Livewire::test(ItemPropertyManager::class)
            ->call('edit', $property->id)
            ->assertSet('form.attr_value', 'Чорний')
            ->set('form.attr_value', 'Білий')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('item_properties', [
            'id' => $property->id,
            'attr_value' => 'Білий'
        ]);
    }

    public function test_can_delete_item_property()
    {
        $property = ItemProperty::create([
            'attribute_id' => $this->attribute->id,
            'attr_value' => 'Зелений'
        ]);

        Livewire::test(ItemPropertyManager::class)
            ->call('delete', $property->id);

        $this->assertDatabaseMissing('item_properties', [
            'id' => $property->id,
        ]);
    }

    public function test_can_search_item_properties()
    {
        $prop1 = ItemProperty::create([
            'attribute_id' => $this->attribute->id,
            'attr_value' => 'SEARCH_VALUE_1'
        ]);

        $prop2 = ItemProperty::create([
            'attribute_id' => $this->attribute->id,
            'attr_value' => 'SEARCH_VALUE_2'
        ]);

        Livewire::test(ItemPropertyManager::class)
            ->set('search', 'SEARCH_VALUE_1')
            ->assertSee('SEARCH_VALUE_1')
            ->assertDontSee('SEARCH_VALUE_2');
    }

    public function test_can_filter_item_properties_by_attribute()
    {
        $otherAttribute = AttributeDictionary::create([
            'name' => 'Розмір'
        ]);

        $prop1 = ItemProperty::create([
            'attribute_id' => $this->attribute->id,
            'attr_value' => 'FilterVal1'
        ]);

        $prop2 = ItemProperty::create([
            'attribute_id' => $otherAttribute->id,
            'attr_value' => 'FilterVal2'
        ]);

        Livewire::test(ItemPropertyManager::class)
            ->set('filterAttribute', [$otherAttribute->id])
            ->assertSee('FilterVal2')
            ->assertDontSee('FilterVal1');
    }

    public function test_item_property_lifecycle_crud()
    {
        // 1. Створення (Create)
        Livewire::test(ItemPropertyManager::class)
            ->call('create')
            ->set('form.attribute_id', $this->attribute->id)
            ->set('form.attr_value', 'Lifecycle Val')
            ->call('store')
            ->assertHasNoErrors();

        $property = ItemProperty::where('attr_value', 'Lifecycle Val')->first();
        $this->assertNotNull($property);

        // 2. Редагування (Update)
        Livewire::test(ItemPropertyManager::class)
            ->call('edit', $property->id)
            ->assertSet('form.attr_value', 'Lifecycle Val')
            ->set('form.attr_value', 'Lifecycle Val Updated')
            ->call('store')
            ->assertHasNoErrors();

        $property->refresh();
        $this->assertEquals('Lifecycle Val Updated', $property->attr_value);

        // 3. Видалення (Delete)
        Livewire::test(ItemPropertyManager::class)
            ->call('delete', $property->id);

        $this->assertDatabaseMissing('item_properties', [
            'id' => $property->id,
        ]);
    }
}
