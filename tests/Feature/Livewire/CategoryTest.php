<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Admin\CategoryManager;
use App\Models\EquipmentCategory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CategoryTest extends TestCase
{
    use DatabaseTransactions;

    public function test_renders_successfully()
    {
        Livewire::test(CategoryManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_category()
    {
        Livewire::test(CategoryManager::class)
            ->call('create')
            ->set('form.category_name', 'Мережеве обладнання')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('categories_tz', [
            'category_name' => 'Мережеве обладнання',
        ]);
    }

    public function test_validation_fails_on_empty_fields()
    {
        Livewire::test(CategoryManager::class)
            ->call('create')
            ->set('form.category_name', '') // required
            ->call('store')
            ->assertHasErrors(['form.category_name']);
    }
}
