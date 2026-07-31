<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Admin\AssetManager;
use App\Models\Asset;
use App\Models\BaseComponent;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class AssetTest extends TestCase
{
    use DatabaseTransactions;

    private Equipment $equipment;

    private BaseComponent $baseComponent;

    protected function setUp(): void
    {
        parent::setUp();

        // Create standard mock data for tests
        $this->equipment = Equipment::create([
            'inv_number' => 999004,
            'account_name' => 'PC',
            'status' => 'В експлуатації',
        ]);

        $this->baseComponent = BaseComponent::create([
            'component_name' => 'Системний блок',
        ]);
    }

    public function test_renders_successfully()
    {
        Livewire::test(AssetManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_asset()
    {
        Livewire::test(AssetManager::class)
            ->call('create')
            ->set('form.equipment_id', $this->equipment->id)
            ->set('form.base_component_id', $this->baseComponent->id)
            ->set('form.status', 'Працює')
            ->set('form.serial_number', 'SN999001')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('assets', [
            'equipment_id' => $this->equipment->id,
            'base_component_id' => $this->baseComponent->id,
            'status' => 'Працює',
            'serial_number' => 'SN999001',
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(AssetManager::class)
            ->call('create')
            ->call('store')
            ->assertHasErrors(['form.equipment_id', 'form.base_component_id']);
    }

    public function test_can_edit_asset()
    {
        $asset = Asset::create([
            'equipment_id' => $this->equipment->id,
            'base_component_id' => $this->baseComponent->id,
            'status' => 'Працює',
            'serial_number' => 'SN999001',
        ]);

        Livewire::test(AssetManager::class)
            ->call('edit', $asset->id)
            ->assertSet('form.serial_number', 'SN999001')
            ->set('form.serial_number', 'SN999001_UPDATED')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'serial_number' => 'SN999001_UPDATED',
        ]);
    }

    public function test_can_delete_asset()
    {
        $asset = Asset::create([
            'equipment_id' => $this->equipment->id,
            'base_component_id' => $this->baseComponent->id,
            'status' => 'Працює',
            'serial_number' => 'SN999002',
        ]);

        Livewire::test(AssetManager::class)
            ->call('delete', $asset->id);

        $this->assertDatabaseMissing('assets', [
            'id' => $asset->id,
        ]);
    }

    public function test_can_search_assets()
    {
        $asset1 = Asset::create([
            'equipment_id' => $this->equipment->id,
            'base_component_id' => $this->baseComponent->id,
            'status' => 'Працює',
            'serial_number' => 'SEARCH_ASSET_1',
        ]);

        $asset2 = Asset::create([
            'equipment_id' => $this->equipment->id,
            'base_component_id' => $this->baseComponent->id,
            'status' => 'Працює',
            'serial_number' => 'SEARCH_ASSET_2',
        ]);

        Livewire::test(AssetManager::class)
            ->set('search', 'SEARCH_ASSET_1')
            ->assertSee('SEARCH_ASSET_1')
            ->assertDontSee('SEARCH_ASSET_2');
    }

    public function test_can_filter_assets_by_status()
    {
        $asset1 = Asset::create([
            'equipment_id' => $this->equipment->id,
            'base_component_id' => $this->baseComponent->id,
            'status' => 'Працює',
            'serial_number' => 'FILTER_ASSET_1',
        ]);

        $asset2 = Asset::create([
            'equipment_id' => $this->equipment->id,
            'base_component_id' => $this->baseComponent->id,
            'status' => 'В ремонті',
            'serial_number' => 'FILTER_ASSET_2',
        ]);

        Livewire::test(AssetManager::class)
            ->set('filterStatus', ['В ремонті'])
            ->assertSee('FILTER_ASSET_2')
            ->assertDontSee('FILTER_ASSET_1');
    }

    public function test_asset_lifecycle_crud()
    {
        // 1. Створення (Create)
        Livewire::test(AssetManager::class)
            ->call('create')
            ->set('form.equipment_id', $this->equipment->id)
            ->set('form.base_component_id', $this->baseComponent->id)
            ->set('form.status', 'Працює')
            ->set('form.serial_number', 'SN999003')
            ->call('store')
            ->assertHasNoErrors();

        $asset = Asset::where('serial_number', 'SN999003')->first();
        $this->assertNotNull($asset);

        // 2. Редагування (Update)
        Livewire::test(AssetManager::class)
            ->call('edit', $asset->id)
            ->assertSet('form.serial_number', 'SN999003')
            ->set('form.serial_number', 'SN999003_UPDATED')
            ->call('store')
            ->assertHasNoErrors();

        $asset->refresh();
        $this->assertEquals('SN999003_UPDATED', $asset->serial_number);

        // 3. Видалення (Delete)
        Livewire::test(AssetManager::class)
            ->call('delete', $asset->id);

        $this->assertDatabaseMissing('assets', [
            'id' => $asset->id,
        ]);
    }

    public function test_cascading_filters_for_category_and_base_components()
    {
        $cat1 = EquipmentCategory::create(['category_name' => 'Категорія 1']);
        $cat2 = EquipmentCategory::create(['category_name' => 'Категорія 2']);

        $comp1 = BaseComponent::create(['component_name' => 'Компонент Кат 1', 'category_id' => $cat1->id]);
        $comp2 = BaseComponent::create(['component_name' => 'Компонент Кат 2', 'category_id' => $cat2->id]);

        Livewire::test(AssetManager::class)
            ->set('filterCategory', [$cat1->id])
            ->assertViewHas('baseComponentsList', function ($list) use ($comp1, $comp2) {
                return $list->pluck('id')->contains($comp1->id) && ! $list->pluck('id')->contains($comp2->id);
            })
            ->set('filterBaseComponent', [(string) $comp1->id, (string) $comp2->id])
            ->set('filterCategory', [$cat2->id])
            ->assertSet('filterBaseComponent', [(string) $comp2->id]);
    }
}
