<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\SupplierManager;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SupplierTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(SupplierManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_supplier()
    {
        Livewire::test(SupplierManager::class)
            ->call('create')
            ->set('form.supplier_name', 'Global Tech IT')
            ->set('form.tax_code', '12345678')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('suppliers', [
            'supplier_name' => 'Global Tech IT',
            'tax_code' => '12345678',
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(SupplierManager::class)
            ->call('create')
            ->set('form.supplier_name', '') // required
            ->call('store')
            ->assertHasErrors(['form.supplier_name']);
    }
}
