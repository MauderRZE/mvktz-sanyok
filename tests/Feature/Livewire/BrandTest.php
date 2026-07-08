<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\BrandManager;
use App\Models\BrandTz;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BrandTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(BrandManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_brand()
    {
        Livewire::test(BrandManager::class)
            ->call('create')
            ->set('form.brandtz_name', 'Lenovo_Test_999')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('brands_tz', [
            'brandtz_name' => 'Lenovo_Test_999',
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(BrandManager::class)
            ->call('create')
            ->set('form.brandtz_name', '') // required
            ->call('store')
            ->assertHasErrors(['form.brandtz_name']);
    }
}
