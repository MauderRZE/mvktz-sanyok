<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Http\Livewire\Admin\EquipmentManager;
use App\Http\Livewire\Admin\EmployeeManager;
use App\Http\Livewire\Admin\CategoryManager;
use App\Http\Livewire\Admin\TypeManager;
use App\Http\Livewire\Admin\UserManager;
use App\Http\Livewire\Admin\BaseComponentManager;
use App\Http\Livewire\Admin\BaseMaterialManager;
use App\Http\Livewire\Admin\SupplierManager;
use App\Http\Livewire\Admin\LocationManager;
use App\Http\Livewire\Admin\MaintenanceTypeManager;
use App\Http\Livewire\Admin\ContractManager;
use App\Http\Livewire\Admin\EquipmentComponentManager;
use App\Http\Livewire\Admin\EquipmentComplaintManager;
use App\Http\Livewire\Admin\EquipmentMovementManager;
use App\Http\Livewire\Admin\LowValueMaterialManager;
use App\Http\Livewire\Admin\MaintenanceLogManager;
use App\Http\Livewire\Admin\SoftwareLicenseManager;
use App\Http\Livewire\Admin\TypeRequirementManager;
use App\Http\Livewire\Admin\SystemErrorManager;
use App\Http\Livewire\Admin\BrandManager;
use App\Http\Livewire\Admin\DepartmentManager;
use App\Http\Livewire\Admin\OrganizationManager;
use App\Http\Livewire\Admin\RetirementActManager;
use App\Http\Livewire\Admin\WriteOffActManager;

class LivewireControllersTest extends TestCase
{
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Retrieve or create admin user for testing auth routes
        $this->user = User::firstOrCreate(
            ['login' => 'admin'],
            [
                'name' => 'admin',
                'password' => bcrypt('$B00ster!')
            ]
        );
    }

    /**
     * Test that all Livewire admin routes are protected by auth middleware.
     */
    public function test_admin_routes_redirect_guests()
    {
        $routes = [
            'admin.equipment',
            'admin.employees',
            'admin.categories',
            'admin.types',
            'admin.users',
            'admin.base-components',
            'admin.base-materials',
            'admin.suppliers',
            'admin.locations',
            'admin.maintenance-types',
            'admin.contracts',
            'admin.components',
            'admin.complaints',
            'admin.movements',
            'admin.low-value-materials',
            'admin.maintenance-logs',
            'admin.software-licenses',
            'admin.type-requirements',
            'admin.system-errors',
            'admin.brands',
            'admin.departments',
            'admin.organizations',
            'admin.retirement-acts',
            'admin.write-off-acts',
        ];

        foreach ($routes as $route) {
            $response = $this->get(route($route));
            $response->assertRedirect(route('login'));
        }
    }

    /**
     * Test that all Livewire admin routes render successfully for authenticated users.
     */
    public function test_admin_routes_render_for_authenticated_users()
    {
        $routes = [
            'admin.equipment',
            'admin.employees',
            'admin.categories',
            'admin.types',
            'admin.users',
            'admin.base-components',
            'admin.base-materials',
            'admin.suppliers',
            'admin.locations',
            'admin.maintenance-types',
            'admin.contracts',
            'admin.components',
            'admin.complaints',
            'admin.movements',
            'admin.low-value-materials',
            'admin.maintenance-logs',
            'admin.software-licenses',
            'admin.type-requirements',
            'admin.system-errors',
            'admin.brands',
            'admin.departments',
            'admin.organizations',
            'admin.retirement-acts',
            'admin.write-off-acts',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($this->user)->get(route($route));
            $response->assertStatus(200);
        }
    }

    /**
     * Test that all Livewire components can be mounted successfully.
     */
    public function test_all_livewire_components_can_be_mounted()
    {
        $components = [
            EquipmentManager::class,
            EmployeeManager::class,
            CategoryManager::class,
            TypeManager::class,
            UserManager::class,
            BaseComponentManager::class,
            BaseMaterialManager::class,
            SupplierManager::class,
            LocationManager::class,
            MaintenanceTypeManager::class,
            ContractManager::class,
            EquipmentComponentManager::class,
            EquipmentComplaintManager::class,
            EquipmentMovementManager::class,
            LowValueMaterialManager::class,
            MaintenanceLogManager::class,
            SoftwareLicenseManager::class,
            TypeRequirementManager::class,
            SystemErrorManager::class,
            BrandManager::class,
            DepartmentManager::class,
            OrganizationManager::class,
            RetirementActManager::class,
            WriteOffActManager::class,
        ];

        $this->actingAs($this->user);

        foreach ($components as $component) {
            Livewire::test($component)
                ->assertStatus(200);
        }
    }
}
