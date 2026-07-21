<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Admin\ContractManager;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class ContractTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(ContractManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_contract()
    {
        $supplier = Supplier::create([
            'supplier_name' => 'Test Supplier',
        ]);

        Livewire::test(ContractManager::class)
            ->call('create')
            ->set('form.contract_number', '№123/2026_Test_999')
            ->set('form.contract_date', '2026-07-08')
            ->set('form.supplier_id', $supplier->id)
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('purchases', [
            'contract_number' => '№123/2026_Test_999',
            'supplier_id' => $supplier->id,
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(ContractManager::class)
            ->call('create')
            ->call('store')
            ->assertHasErrors(['form.contract_number', 'form.contract_date', 'form.supplier_id']);
    }

    public function test_validation_fails_on_invalid_year()
    {
        $supplier = Supplier::create([
            'supplier_name' => 'Test Supplier 2',
        ]);

        Livewire::test(ContractManager::class)
            ->call('create')
            ->set('form.contract_number', '65')
            ->set('form.contract_date', '20218-02-07')
            ->set('form.supplier_id', $supplier->id)
            ->call('store')
            ->assertHasErrors(['form.contract_date']);
    }
}
