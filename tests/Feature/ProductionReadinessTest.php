<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\Equipment;
use App\Models\LowValueMaterial;
use App\Models\SoftwareLicense;
use App\Models\EquipmentComponent;

class ProductionReadinessTest extends TestCase
{
    /**
     * Test that there are no orphaned records violating foreign key relationships.
     * This ensures referential integrity in production database.
     */
    public function test_no_orphaned_records_exist()
    {
        // 1. low_value_materials must have valid material_id in base_materials
        $orphanedMaterials = DB::table('low_value_materials')
            ->leftJoin('base_materials', 'low_value_materials.material_id', '=', 'base_materials.id')
            ->whereNull('base_materials.id')
            ->count();
        $this->assertEquals(0, $orphanedMaterials, "Orphaned records found in 'low_value_materials' (invalid material_id)");

        // 2. equipment must have valid equipment_type_id in equipment_types
        $orphanedEquipmentTypes = DB::table('equipment')
            ->leftJoin('equipment_types', 'equipment.equipment_type_id', '=', 'equipment_types.id')
            ->whereNull('equipment_types.id')
            ->count();
        $this->assertEquals(0, $orphanedEquipmentTypes, "Orphaned records found in 'equipment' (invalid equipment_type_id)");

        // 3. software_licenses must have valid equipment_id in equipment
        $orphanedLicenses = DB::table('software_licenses')
            ->leftJoin('equipment', 'software_licenses.equipment_id', '=', 'equipment.id')
            ->whereNull('equipment.id')
            ->count();
        $this->assertEquals(0, $orphanedLicenses, "Orphaned records found in 'software_licenses' (invalid equipment_id)");
    }

    /**
     * Test that the Eloquent relationships resolve correctly for existing records.
     */
    public function test_relationships_resolve_correctly()
    {
        // Check low value materials relations
        LowValueMaterial::with(['material', 'equipment', 'contract'])->chunk(100, function ($materials) {
            foreach ($materials as $material) {
                $this->assertNotNull($material->material, "Material relation must be loaded for LowValueMaterial ID: {$material->id}");
            }
        });

        // Check equipment relations
        Equipment::with(['type', 'components', 'movements', 'complaints', 'maintenanceLogs'])->chunk(100, function ($equipments) {
            foreach ($equipments as $equipment) {
                $this->assertTrue(true); // Ensure eager loading doesn't throw exceptions
            }
        });
    }

    /**
     * Test that essential indexes exist on heavy tables for fast querying.
     */
    public function test_critical_indexes_exist()
    {
        $tablesWithIndexes = [
            'low_value_materials' => ['material_id', 'equipment_id', 'contract_id'],
            'equipment' => ['equipment_type_id'],
            'equipment_components' => ['equipment_id', 'component_type_id'],
            'software_licenses' => ['equipment_id'],
        ];

        foreach ($tablesWithIndexes as $table => $columns) {
            $indexes = DB::select("SHOW INDEX FROM `{$table}`");
            $indexedColumns = collect($indexes)->pluck('Column_name')->unique()->toArray();

            foreach ($columns as $column) {
                $this->assertContains(
                    $column,
                    $indexedColumns,
                    "Column '{$column}' in table '{$table}' is missing a database index, which is critical for production performance!"
                );
            }
        }
    }

    /**
     * Test secure environment configuration patterns for production.
     */
    public function test_secure_environment_configuration()
    {
        // App key must be set
        $appKey = config('app.key');
        $this->assertNotEmpty($appKey, "APP_KEY is empty. A secure key must be generated.");
        $this->assertStringStartsWith('base64:', $appKey, "APP_KEY should be a secure base64 encoded string.");
    }
}
