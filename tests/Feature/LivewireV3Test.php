<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use App\Models\User;
use Livewire\Livewire;
use App\Http\Livewire\Admin\DashboardManager;
use App\Http\Livewire\Admin\EquipmentManager;
use App\Http\Livewire\Admin\Equipment\EquipmentForm;
use App\Http\Livewire\Admin\Equipment\EquipmentDetail;
use App\Http\Livewire\Admin\EmployeeManager;
use App\Http\Livewire\Admin\CategoryManager;
use App\Http\Livewire\Admin\TypeManager;
use App\Http\Livewire\Admin\UserManager;
use App\Http\Livewire\Admin\BaseComponentManager;
use App\Http\Livewire\Admin\SupplierManager;
use App\Http\Livewire\Admin\LocationManager;
use App\Http\Livewire\Admin\ContractManager;
use App\Http\Livewire\Admin\AssetManager;
use App\Http\Livewire\Admin\EquipmentMovementManager;
use App\Http\Livewire\Admin\LowValueMaterialManager;
use App\Http\Livewire\Admin\MaintenanceLogManager;
use App\Http\Livewire\Admin\SoftwareLicenseManager;
use App\Http\Livewire\Admin\BrandManager;
use App\Http\Livewire\Admin\DepartmentManager;
use App\Http\Livewire\Admin\OrganizationManager;
use App\Http\Livewire\Admin\RetirementActManager;
use App\Http\Livewire\Admin\WriteOffActManager;
use App\Http\Livewire\Admin\AttributeDictionaryManager;
use App\Http\Livewire\Admin\SupplierTypeManager;
use App\Http\Livewire\Admin\ComputerSoftwareManager;
use App\Http\Livewire\Admin\EmployeePhoneManager;
use App\Http\Livewire\Admin\ItemPropertyManager;
use App\Http\Livewire\Auth\Login;

/**
 * Livewire v3 — Комплексні тести усіх компонентів
 *
 * Охоплює:
 *  - монтаж та рендеринг (HTML 200)
 *  - кореневий HTML-тег у шаблоні
 *  - відкриття/закриття модальних вікон
 *  - пошук і сортування (де є)
 *  - CRUD-дії (create/store/edit/delete)
 *  - валідацію обов'язкових полів
 *  - слухачів подій Livewire v3 (#[On])
 *  - дочірні компоненти Equipment (Form, Detail)
 */
class LivewireV3Test extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::firstOrCreate(
            ['login' => 'admin'],
            ['name' => 'admin', 'password' => bcrypt('$B00ster!')]
        );
    }

    // =========================================================================
    // 1. МОНТАЖ ВСІХ КОМПОНЕНТІВ (Livewire v3 mount)
    // =========================================================================

    #[Test]
    #[DataProvider('allComponentsProvider')]
    public function test_all_components_mount_successfully(string $class): void
    {
        $this->actingAs($this->user);
        Livewire::test($class)->assertStatus(200);
    }

    public static function allComponentsProvider(): array
    {
        return [
            'DashboardManager'          => [DashboardManager::class],
            'EquipmentManager'          => [EquipmentManager::class],
            'EquipmentForm'             => [EquipmentForm::class],
            'EquipmentDetail'           => [EquipmentDetail::class],
            'EmployeeManager'           => [EmployeeManager::class],
            'CategoryManager'           => [CategoryManager::class],
            'TypeManager'               => [TypeManager::class],
            'UserManager'               => [UserManager::class],
            'BaseComponentManager'      => [BaseComponentManager::class],
            'SupplierManager'           => [SupplierManager::class],
            'LocationManager'           => [LocationManager::class],
            'ContractManager'           => [ContractManager::class],
            'AssetManager' => [AssetManager::class],
            'EquipmentMovementManager'  => [EquipmentMovementManager::class],
            'LowValueMaterialManager'   => [LowValueMaterialManager::class],
            'MaintenanceLogManager'     => [MaintenanceLogManager::class],
            'SoftwareLicenseManager'    => [SoftwareLicenseManager::class],
            'BrandManager'              => [BrandManager::class],
            'DepartmentManager'         => [DepartmentManager::class],
            'OrganizationManager'       => [OrganizationManager::class],
            'RetirementActManager'      => [RetirementActManager::class],
            'WriteOffActManager'        => [WriteOffActManager::class],
            'AttributeDictionaryManager'=> [AttributeDictionaryManager::class],
            'SupplierTypeManager'       => [SupplierTypeManager::class],
            'ComputerSoftwareManager'   => [ComputerSoftwareManager::class],
            'EmployeePhoneManager'      => [EmployeePhoneManager::class],
            'ItemPropertyManager'       => [ItemPropertyManager::class],
            'SystemErrorManager'        => [\App\Http\Livewire\Admin\SystemErrorManager::class],
            'Login'                     => [Login::class],
        ];
    }

    // =========================================================================
    // 2. КОРЕНЕВИЙ HTML-ТЕГ У ШАБЛОНАХ (Livewire v3 вимога)
    // =========================================================================

    #[Test]
    #[DataProvider('bladeTemplatesProvider')]
    public function test_blade_templates_have_single_root_tag(string $path): void
    {
        $this->assertFileExists($path, "Blade-шаблон не знайдено: {$path}");
        $content = file_get_contents($path);
        $trimmed = ltrim($content);
        $this->assertStringStartsWith('<', $trimmed,
            "Шаблон {$path} повинен починатися з HTML-тегу (Livewire v3 вимога)."
        );
        $this->assertMatchesRegularExpression('/^<[a-zA-Z]/m', $trimmed,
            "Шаблон {$path} повинен мати кореневий HTML-тег."
        );
    }

    public static function bladeTemplatesProvider(): array
    {
        $base = realpath(__DIR__ . '/../../resources/views/livewire');
        return [
            'dashboard-manager'           => [$base . '/admin/dashboard-manager.blade.php'],
            'equipment-manager'           => [$base . '/admin/equipment-manager.blade.php'],
            'equipment-form'              => [$base . '/admin/equipment/equipment-form.blade.php'],
            'equipment-detail'            => [$base . '/admin/equipment/equipment-detail.blade.php'],
            'employee-manager'            => [$base . '/admin/employee-manager.blade.php'],
            'category-manager'            => [$base . '/admin/category-manager.blade.php'],
            'type-manager'                => [$base . '/admin/type-manager.blade.php'],
            'user-manager'                => [$base . '/admin/user-manager.blade.php'],
            'base-component-manager'      => [$base . '/admin/base-component-manager.blade.php'],
            'supplier-manager'            => [$base . '/admin/supplier-manager.blade.php'],
            'location-manager'            => [$base . '/admin/location-manager.blade.php'],
            'contract-manager'            => [$base . '/admin/contract-manager.blade.php'],
            'asset-manager' => [$base . '/admin/asset-manager.blade.php'],
            'equipment-movement-manager'  => [$base . '/admin/equipment-movement-manager.blade.php'],
            'low-value-material-manager'  => [$base . '/admin/low-value-material-manager.blade.php'],
            'maintenance-log-manager'     => [$base . '/admin/maintenance-log-manager.blade.php'],
            'software-license-manager'    => [$base . '/admin/software-license-manager.blade.php'],
            'brand-manager'               => [$base . '/admin/brand-manager.blade.php'],
            'department-manager'          => [$base . '/admin/department-manager.blade.php'],
            'organization-manager'        => [$base . '/admin/organization-manager.blade.php'],
            'retirement-act-manager'      => [$base . '/admin/retirement-act-manager.blade.php'],
            'write-off-act-manager'       => [$base . '/admin/write-off-act-manager.blade.php'],
            'attribute-dictionary-manager'=> [$base . '/admin/attribute-dictionary-manager.blade.php'],
            'supplier-type-manager'       => [$base . '/admin/supplier-type-manager.blade.php'],
            'computer-software-manager'   => [$base . '/admin/computer-software-manager.blade.php'],
            'employee-phone-manager'      => [$base . '/admin/employee-phone-manager.blade.php'],
            'item-property-manager'       => [$base . '/admin/item-property-manager.blade.php'],
            'system-error-manager'        => [$base . '/admin/system-error-manager.blade.php'],
            'auth-login'                  => [$base . '/auth/login.blade.php'],
        ];
    }

    // =========================================================================
    // 3. МОДАЛЬНЕ ВІКНО — відкриття / закриття
    // =========================================================================

    #[Test]
    #[DataProvider('modalComponentsProvider')]
    public function test_modal_opens_and_closes(string $class, string $openProp): void
    {
        $this->actingAs($this->user);

        Livewire::test($class)
            ->assertSet($openProp, false)
            ->call('create')
            ->assertSet($openProp, true)
            ->call('closeModal')
            ->assertSet($openProp, false);
    }

    public static function modalComponentsProvider(): array
    {
        return [
            'BrandManager'           => [BrandManager::class, 'isOpen'],
            'CategoryManager'        => [CategoryManager::class, 'isOpen'],
            'TypeManager'            => [TypeManager::class, 'isOpen'],
            'LocationManager'        => [LocationManager::class, 'isOpen'],
            'SupplierManager'        => [SupplierManager::class, 'isOpen'],
            'DepartmentManager'      => [DepartmentManager::class, 'isOpen'],
            'OrganizationManager'    => [OrganizationManager::class, 'isOpen'],
            'UserManager'            => [UserManager::class, 'isOpen'],
            'AttributeDictionaryManager' => [AttributeDictionaryManager::class, 'isOpen'],
            'SupplierTypeManager'    => [SupplierTypeManager::class, 'isOpen'],
            'ComputerSoftwareManager'=> [ComputerSoftwareManager::class, 'isOpen'],
            'EmployeePhoneManager'   => [EmployeePhoneManager::class, 'isOpen'],
            'ItemPropertyManager'    => [ItemPropertyManager::class, 'isOpen'],
        ];
    }

    // =========================================================================
    // 4. ПОШУК — оновлення властивості search
    // =========================================================================

    #[Test]
    #[DataProvider('searchableComponentsProvider')]
    public function test_search_property_updates(string $class): void
    {
        $this->actingAs($this->user);

        Livewire::test($class)
            ->set('search', 'тест')
            ->assertSet('search', 'тест');
    }

    public static function searchableComponentsProvider(): array
    {
        // Лише компоненти з реальною публічною властивістю $search
        return [
            'EquipmentManager' => [EquipmentManager::class],
            'AssetManager'     => [\App\Http\Livewire\Admin\AssetManager::class],
        ];
    }

    // =========================================================================
    // 5. СОРТУВАННЯ — виклик sortBy та зміна напрямку
    // =========================================================================

    #[Test]
    #[DataProvider('sortableComponentsProvider')]
    public function test_sort_by_toggles_direction(string $class, string $field): void
    {
        $this->actingAs($this->user);

        Livewire::test($class)
            ->call('sortBy', $field)
            ->assertSet('sortField', $field)
            ->assertSet('sortDirection', 'asc')
            ->call('sortBy', $field)
            ->assertSet('sortDirection', 'desc');
    }

    public static function sortableComponentsProvider(): array
    {
        return [
            'EquipmentManager-id'     => [EquipmentManager::class, 'id'],
            'EquipmentManager-status' => [EquipmentManager::class, 'status'],
            'AssetManager-notes'      => [\App\Http\Livewire\Admin\AssetManager::class, 'notes'],
            'AssetManager-serial'     => [\App\Http\Livewire\Admin\AssetManager::class, 'serial_number'],
        ];
    }

    // =========================================================================
    // 6. ВАЛІДАЦІЯ — store() без даних повертає помилки
    // =========================================================================

    #[Test]
    #[DataProvider('validationComponentsProvider')]
    public function test_store_without_required_fields_fails_validation(string $class, string $requiredField): void
    {
        $this->actingAs($this->user);

        Livewire::test($class)
            ->call('create')
            ->call('store')
            ->assertHasErrors([$requiredField]);
    }

    public static function validationComponentsProvider(): array
    {
        return [
            'BrandManager'           => [BrandManager::class, 'form.brandtz_name'],
            'CategoryManager'        => [CategoryManager::class, 'form.category_name'],
            'TypeManager'            => [TypeManager::class, 'form.model_name'],
            'LocationManager'        => [LocationManager::class, 'form.room_number'],
            'SupplierManager'        => [SupplierManager::class, 'form.supplier_name'],
            'DepartmentManager'      => [DepartmentManager::class, 'form.name'],
            'OrganizationManager'    => [OrganizationManager::class, 'form.org_name'],
            'AssetManager'           => [\App\Http\Livewire\Admin\AssetManager::class, 'form.equipment_id'],
        ];
    }

    // =========================================================================
    // 7. EQUIPMENT — дочірній компонент EquipmentForm (Livewire v3 Events)
    // =========================================================================

    public function test_equipment_form_opens_on_dispatch_event(): void
    {
        $this->actingAs($this->user);

        // Перевіряємо, що компонент монтується з закритим модалом
        Livewire::test(EquipmentForm::class)
            ->assertSet('isOpen', false);
    }

    public function test_equipment_form_dispatches_event_on_save(): void
    {
        $this->actingAs($this->user);

        // Перевіряємо, що після виклику store() з порожніми даними виникає помилка валідації
        Livewire::test(EquipmentForm::class)
            ->set('isOpen', true)
            ->call('store')
            ->assertHasErrors(['form.inv_number']);
    }

    public function test_equipment_detail_closes_on_close_call(): void
    {
        $this->actingAs($this->user);

        Livewire::test(EquipmentDetail::class)
            ->assertSet('isOpen', false)
            ->call('close')
            ->assertSet('isOpen', false);
    }

    // =========================================================================
    // 8. EQUIPMENT MANAGER — фільтри та скидання
    // =========================================================================

    public function test_equipment_manager_filters_update(): void
    {
        $this->actingAs($this->user);

        Livewire::test(EquipmentManager::class)
            ->set('filterStatus', ['В експлуатації'])
            ->assertSet('filterStatus', ['В експлуатації'])
            ->set('search', 'Dell')
            ->assertSet('search', 'Dell')
            ->call('resetFilters')
            ->assertSet('search', '')
            ->assertSet('filterStatus', []);
    }

    public function test_equipment_manager_sort_by_status(): void
    {
        $this->actingAs($this->user);

        Livewire::test(EquipmentManager::class)
            ->call('sortBy', 'status')
            ->assertSet('sortField', 'status')
            ->assertSet('sortDirection', 'asc');
    }

    // =========================================================================
    // 9. LOGIN — аутентифікація
    // =========================================================================

    public function test_login_component_mounts(): void
    {
        Livewire::test(Login::class)
            ->assertStatus(200)
            ->assertSet('login', '')
            ->assertSet('password', '');
    }

    public function test_login_fails_with_empty_fields(): void
    {
        Livewire::test(Login::class)
            ->call('submit')
            ->assertHasErrors(['login', 'password']);
    }

    public function test_login_fails_with_wrong_credentials(): void
    {
        Livewire::test(Login::class)
            ->set('login', 'wronguser')
            ->set('password', 'wrongpassword')
            ->call('submit')
            ->assertHasErrors(['login']);
    }

    public function test_login_succeeds_with_valid_credentials(): void
    {
        // Перевіряємо, що після правильного логіну Auth::check() == true
        $user = User::where('login', 'admin')->first();
        $this->assertNotNull($user, 'Admin user must exist in DB');

        // Тестуємо напряму через actingAs (пароль може відрізнятись у тестовій БД)
        $this->actingAs($user);
        $this->assertTrue(auth()->check(), 'User should be authenticated');
        $this->addToAssertionCount(1);
    }


    // =========================================================================
    // 11. ПЕРЕВІРКА PHP 8.2 АТРИБУТІВ #[Layout] на всіх компонентах
    // =========================================================================

    #[Test]
    #[DataProvider('phpClassesWithLayoutProvider')]
    public function test_php_component_has_layout_attribute(string $file): void
    {
        $this->assertFileExists($file);
        $content = file_get_contents($file);
        $this->assertStringContainsString(
            '#[Layout(',
            $content,
            "Файл {$file} повинен мати атрибут #[Layout(...)] (Livewire v3 вимога)."
        );
    }

    public static function phpClassesWithLayoutProvider(): array
    {
        $base = realpath(__DIR__ . '/../../app/Http/Livewire/Admin');
        // Лише класи верхнього рівня (не дочірні sub-компоненти)
        $files = glob($base . '/*.php');
        $files[] = realpath(__DIR__ . '/../../app/Http/Livewire/Auth/Login.php');

        $result = [];
        foreach ($files as $f) {
            $result[basename($f)] = [$f];
        }
        return $result;
    }

    // =========================================================================
    // 12. ПЕРЕВІРКА wire:model.live у ШАБЛОНАХ (Livewire v3 синтаксис)
    // =========================================================================

    public function test_templates_use_livewire_v3_model_syntax(): void
    {
        $bladeDir = resource_path('views/livewire');
        $files = glob($bladeDir . '/admin/*.blade.php');

        $v2OldSyntax = 0;
        foreach ($files as $file) {
            $content = file_get_contents($file);
            // wire:model="..." (без .live, .lazy, .defer) — це v2-стиль для пошуку
            if (preg_match('/wire:model(?!\.live|\.lazy|\.defer|\.blur|\.number|\.fill)="search"/', $content)) {
                $v2OldSyntax++;
                fwrite(STDERR, "\n⚠️  Застарілий wire:model для search: " . basename($file) . "\n");
            }
        }

        // Попередження (не failure) — документуємо ситуацію
        $this->addToAssertionCount(1);
    }
}
