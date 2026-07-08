<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Livewire\Livewire;
use App\Http\Livewire\Admin\DashboardManager;
use App\Models\User;
use App\Models\Equipment;
use App\Models\Employee;
use App\Models\SoftwareLicense;

class DashboardTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::firstOrCreate(
            ['login' => 'admin'],
            ['name' => 'admin', 'password' => bcrypt('password')]
        );
    }

    #[Test]
    public function dashboard_renders_successfully()
    {
        $this->actingAs($this->user);

        // Створюємо трохи тестових даних
        Equipment::create(['inv_number' => random_int(1000000, 9999999), 'status' => 'в експлуатації', 'account_name' => 'PC1']);
        Equipment::create(['inv_number' => random_int(1000000, 9999999), 'status' => 'в аренді', 'account_name' => 'PC2']);
        Employee::create(['first_name' => 'John', 'last_name' => 'Doe', 'position' => 'Admin']);

        Livewire::test(DashboardManager::class)
            ->assertStatus(200)
            ->assertViewHas('stats')
            ->assertViewHas('recentMaintenance');
    }

    #[Test]
    public function root_route_redirects_to_dashboard()
    {
        $this->actingAs($this->user);
        $response = $this->get('/');
        $response->assertRedirect(route('admin.dashboard'));
    }

    #[Test]
    public function admin_route_redirects_to_dashboard()
    {
        $this->actingAs($this->user);
        $response = $this->get('/admin');
        $response->assertRedirect(route('admin.dashboard'));
    }
}
