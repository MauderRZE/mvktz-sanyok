<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\TypeManager;
use App\Models\EquipmentType;
use App\Models\BrandTz;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TypeTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(TypeManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_equipment_type()
    {
        $brand = BrandTz::firstOrCreate([
            'brandtz_name' => 'Dell'
        ]);

        Livewire::test(TypeManager::class)
            ->call('create')
            ->set('form.model_name', 'OptiPlex 3080_Test_999')
            ->set('form.brand_id', $brand->id)
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('models_tz', [
            'model_name' => 'OptiPlex 3080_Test_999',
            'brand_id' => $brand->id,
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(TypeManager::class)
            ->call('create')
            ->set('form.model_name', '') // required
            ->call('store')
            ->assertHasErrors(['form.model_name', 'form.brand_id']);
    }
}
