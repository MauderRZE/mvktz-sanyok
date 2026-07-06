<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\BaseComponent;
use App\Models\Contract;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\Asset;
use App\Models\EquipmentMovement;
use App\Models\EquipmentType;
use App\Models\Location;
use App\Models\LowValueMaterial;
use App\Models\MaintenanceLog;
use App\Models\SoftwareLicense;
use App\Models\Supplier;
use App\Models\User;
use App\Models\BrandTz;
use App\Models\Department;
use App\Models\LocationHolder;
use App\Models\Organization;
use App\Models\SupplierType;
use App\Models\EquipmentRetirementAct;
use App\Models\LowValueWriteOffAct;

class DatabaseRelationshipsTest extends TestCase
{
    /**
     * Test that all relationships on all Eloquent models can be resolved/loaded without exceptions.
     */
    public function test_all_model_relationships_can_be_eager_loaded()
    {
        $relationsMap = [
            Contract::class => ['supplier'],
            Equipment::class => ['assets', 'movements', 'maintenanceLogs'],
            Asset::class => ['equipment', 'componentType', 'baseComponent', 'model', 'location', 'holder', 'parentAsset', 'childAssets', 'lowValueMaterial', 'writeOffAct', 'repairs', 'computerSoftwares', 'itemProperties'],
            EquipmentMovement::class => ['equipment', 'employee', 'asset', 'fromHolder', 'toHolder'],
            EquipmentType::class => ['brand', 'assets'],
            LowValueMaterial::class => ['contract'],
            MaintenanceLog::class => ['asset'],
            SoftwareLicense::class => [],
            BrandTz::class => ['equipmentTypes'],
            Department::class => ['employees'],
            Employee::class => ['phones'],
            LocationHolder::class => ['employee', 'organization', 'assets'],
            Organization::class => ['locationHolders'],
            SupplierType::class => ['suppliers'],
            EquipmentRetirementAct::class => ['equipment'],
            LowValueWriteOffAct::class => ['assets'],
            \App\Models\AttributeDictionary::class => ['itemProperties'],
            \App\Models\ComputerSoftware::class => ['computer', 'license'],
            \App\Models\EmployeePhone::class => ['employee'],
            \App\Models\ItemProperty::class => ['asset', 'nomenclature', 'attribute'],
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
