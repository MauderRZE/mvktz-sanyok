<?php

namespace Tests\Feature;

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

class DatabaseRelationshipsTest extends TestCase
{
    /**
     * Test that all relationships on all Eloquent models can be resolved/loaded without exceptions.
     */
    public function test_all_model_relationships_can_be_eager_loaded()
    {
        $relationsMap = [
            Contract::class => ['supplier'],
            Equipment::class => ['type', 'components', 'movements', 'complaints', 'maintenanceLogs'],
            EquipmentComplaint::class => ['equipment'],
            EquipmentComponent::class => ['equipment', 'componentType'],
            EquipmentMovement::class => ['equipment', 'location', 'employee'],
            EquipmentType::class => ['category'],
            LowValueMaterial::class => ['material', 'equipment', 'contract'],
            MaintenanceLog::class => ['equipment', 'maintenanceType'],
            SoftwareLicense::class => ['component'],
            TypeRequirement::class => ['equipmentType', 'componentType'],
        ];

        foreach ($relationsMap as $model => $relations) {
            try {
                // Eager load the relations to verify key definitions
                $model::with($relations)->first();
                $this->assertTrue(true);
            } catch (\Exception $e) {
                $this->fail("Eager loading relations [" . implode(', ', $relations) . "] on model $model failed: " . $e->getMessage());
            }
        }
    }
}
