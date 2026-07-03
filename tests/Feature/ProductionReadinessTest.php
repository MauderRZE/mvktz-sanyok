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
        // 1. assets must have valid equipment_id in equipment (if set)
        $orphanedAssets = DB::table('assets')
            ->leftJoin('equipment', 'assets.equipment_id', '=', 'equipment.id')
            ->whereNotNull('assets.equipment_id')
            ->whereNull('equipment.id')
            ->count();
        $this->assertEquals(0, $orphanedAssets, "Orphaned records found in 'assets' (invalid equipment_id)");

        // 2. movements must have valid equip_id in equipment (if set)
        $orphanedMovements = DB::table('movements')
            ->leftJoin('equipment', 'movements.equip_id', '=', 'equipment.id')
            ->whereNotNull('movements.equip_id')
            ->whereNull('equipment.id')
            ->count();
        $this->assertEquals(0, $orphanedMovements, "Orphaned records found in 'movements' (invalid equip_id)");
    }

    /**
     * Test that the Eloquent relationships resolve correctly for existing records.
     */
    public function test_relationships_resolve_correctly()
    {
        // Check low value materials relations
        LowValueMaterial::with(['material', 'equipment', 'contract'])->chunk(100, function ($materials) {
            foreach ($materials as $material) {
                $this->assertNotNull($material->contract, "Contract relation must be loaded for LowValueMaterial ID: {$material->id}");
            }
        });

        // Check equipment relations
        Equipment::with(['type', 'components', 'movements', 'complaints', 'maintenanceLogs'])->chunk(100, function ($equipments) {
            foreach ($equipments as $equipment) {
                $this->assertTrue(true); // Ensure eager loading doesn't throw exceptions
            }
        });

        $this->assertTrue(true);
    }

    /**
     * Test that essential indexes exist on heavy tables for fast querying.
     */
    public function test_critical_indexes_exist()
    {
        $tablesWithIndexes = [
            'low_value_materials' => ['contract_id'],
            'equipment' => ['purchase_id'],
            'assets' => ['equipment_id', 'base_component_id'],
            'movements' => ['equip_id'],
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
