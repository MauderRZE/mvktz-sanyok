<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\BaseComponentManager;
use App\Models\BaseComponent;
use App\Models\EquipmentCategory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BaseComponentTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(BaseComponentManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_base_component()
    {
        $category = EquipmentCategory::create([
            'category_name' => 'Комплектуючі'
        ]);

        Livewire::test(BaseComponentManager::class)
            ->call('create')
            ->set('form.component_name', 'Системна плата')
            ->set('form.category_id', $category->id)
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('base_components', [
            'component_name' => 'Системна плата',
            'category_id' => $category->id,
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(BaseComponentManager::class)
            ->call('create')
            ->set('form.component_name', '') // required
            ->call('store')
            ->assertHasErrors(['form.component_name']);
    }
}
