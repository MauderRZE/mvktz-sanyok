<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Admin\ItemPropertyManager;
use App\Models\Asset;
use App\Models\AttributeDictionary;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\ItemProperty;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class ItemPropertyTest extends TestCase
{
    use DatabaseTransactions;

    private AttributeDictionary $attribute;

    protected function setUp(): void
    {
        parent::setUp();

        $this->attribute = AttributeDictionary::create([
            'name' => 'Колір',
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
            'attr_value' => 'Чорний',
        ]);

        Livewire::test(ItemPropertyManager::class)
            ->call('edit', $property->id)
            ->assertSet('form.attr_value', 'Чорний')
            ->set('form.attr_value', 'Білий')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('item_properties', [
            'id' => $property->id,
            'attr_value' => 'Білий',
        ]);
    }

    public function test_can_delete_item_property()
    {
        $property = ItemProperty::create([
            'attribute_id' => $this->attribute->id,
            'attr_value' => 'Зелений',
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
            'attr_value' => 'SEARCH_VALUE_1',
        ]);

        $prop2 = ItemProperty::create([
            'attribute_id' => $this->attribute->id,
            'attr_value' => 'SEARCH_VALUE_2',
        ]);

        Livewire::test(ItemPropertyManager::class)
            ->set('search', 'SEARCH_VALUE_1')
            ->assertSee('SEARCH_VALUE_1')
            ->assertDontSee('SEARCH_VALUE_2');
    }

    public function test_can_filter_item_properties_by_attribute()
    {
        $otherAttribute = AttributeDictionary::create([
            'name' => 'Розмір',
        ]);

        $prop1 = ItemProperty::create([
            'attribute_id' => $this->attribute->id,
            'attr_value' => 'FilterVal1',
        ]);

        $prop2 = ItemProperty::create([
            'attribute_id' => $otherAttribute->id,
            'attr_value' => 'FilterVal2',
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

    public function test_can_search_and_sort_by_inv_number()
    {
        $equipment1 = Equipment::create(['inv_number' => 'INV-8888', 'account_name' => 'Equip 1', 'status' => 'В експлуатації']);
        $equipment2 = Equipment::create(['inv_number' => 'INV-1111', 'account_name' => 'Equip 2', 'status' => 'В експлуатації']);

        $compType = EquipmentCategory::firstOrCreate(['category_name' => 'Test Category']);
        $asset1 = Asset::create(['equipment_id' => $equipment1->id, 'category_id' => $compType->id]);
        $asset2 = Asset::create(['equipment_id' => $equipment2->id, 'category_id' => $compType->id]);

        $prop1 = ItemProperty::create([
            'attribute_id' => $this->attribute->id,
            'asset_id' => $asset1->id,
            'attr_value' => 'Val_INV8888',
        ]);

        $prop2 = ItemProperty::create([
            'attribute_id' => $this->attribute->id,
            'asset_id' => $asset2->id,
            'attr_value' => 'Val_INV1111',
        ]);

        Livewire::test(ItemPropertyManager::class)
            ->set('search', 'INV-8888')
            ->assertSee('Val_INV8888')
            ->assertDontSee('Val_INV1111')
            ->call('resetFilters')
            ->call('sortBy', 'inv_number')
            ->assertSet('sortDirection', 'asc')
            ->call('sortBy', 'inv_number')
            ->assertSet('sortDirection', 'desc');
    }
}
