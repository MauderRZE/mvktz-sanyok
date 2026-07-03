<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use App\Models\BaseComponent;
use App\Models\BaseMaterial;
use App\Models\Contract;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\EquipmentComplaint;
use App\Models\EquipmentComponent;
use App\Models\EquipmentMovement;
use App\Models\EquipmentType;
use App\Models\Location;
use App\Models\LowValueMaterial;
use App\Models\MaintenanceLog;
use App\Models\MaintenanceType;
use App\Models\SoftwareLicense;
use App\Models\Supplier;
use App\Models\TypeRequirement;
use App\Models\User;
use App\Models\BrandTz;
use App\Models\Department;
use App\Models\LocationHolder;
use App\Models\Organization;
use App\Models\SupplierType;
use App\Models\EquipmentRetirementAct;
use App\Models\LowValueWriteOffAct;

class DatabaseSchemaTest extends TestCase
{
    /**
     * Test that all expected tables exist in the database.
     */
    public function test_all_tables_exist()
    {
        $tables = [
            'base_components',
            'employee',
            'equipment',
            'categories_tz',
            'assets',
            'movements',
            'models_tz',
            'locations',
            'low_value_materials',
            'repairs',
            'licenses',
            'suppliers',
            'users',
            'attributes_dictionary',
            'brands_tz',
            'computer_software',
            'departments',
            'employee_phones',
            'equipment_retirement_acts',
            'item_properties',
            'location_holders',
            'low_value_write_off_acts',
            'organizations',
            'purchases',
            'supplier_types',
        ];

        foreach ($tables as $table) {
            $this->assertTrue(
                Schema::hasTable($table),
                "Table '$table' does not exist in the database."
            );
        }
    }

    /**
     * Test that Eloquent models can query their tables without errors.
     */
    public function test_all_models_can_query_database()
    {
        $models = [
            BaseComponent::class,
            BaseMaterial::class,
            Contract::class,
            Employee::class,
            Equipment::class,
            EquipmentCategory::class,
            EquipmentComplaint::class,
            EquipmentComponent::class,
            EquipmentMovement::class,
            EquipmentType::class,
            Location::class,
            LowValueMaterial::class,
            MaintenanceLog::class,
            MaintenanceType::class,
            SoftwareLicense::class,
            Supplier::class,
            TypeRequirement::class,
            User::class,
            BrandTz::class,
            Department::class,
            LocationHolder::class,
            Organization::class,
            SupplierType::class,
            EquipmentRetirementAct::class,
            LowValueWriteOffAct::class,
        ];

        foreach ($models as $model) {
            try {
                // Perform a simple count query to ensure table and columns are valid
                $count = $model::count();
                $this->assertIsInt($count, "Failed querying $model");
            } catch (\Exception $e) {
                $this->fail("Querying model $model failed with error: " . $e->getMessage());
            }
        }
    }
}
