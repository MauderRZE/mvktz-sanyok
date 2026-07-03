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
use App\Models\BrandTz;
use App\Models\Department;
use App\Models\EmployeePhone;
use App\Models\LocationHolder;
use App\Models\Organization;
use App\Models\SupplierType;
use App\Models\EquipmentRetirementAct;
use App\Models\LowValueWriteOffAct;
use App\Models\ItemProperty;
use App\Models\AttributeDictionary;
use App\Models\ComputerSoftware;

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
            EquipmentComponent::class => ['equipment', 'componentType', 'baseComponent', 'model', 'location', 'holder', 'parentAsset', 'childAssets', 'lowValueMaterial', 'writeOffAct', 'computerSoftwares', 'repairs'],
            EquipmentMovement::class => ['equipment', 'location', 'employee', 'asset', 'fromHolder', 'toHolder'],
            EquipmentType::class => ['category', 'brand', 'assets'],
            LowValueMaterial::class => ['material', 'equipment', 'contract'],
            MaintenanceLog::class => ['equipment', 'maintenanceType', 'asset'],
            SoftwareLicense::class => ['component'],
            TypeRequirement::class => ['equipmentType', 'componentType', 'component'],
            BrandTz::class => ['equipmentTypes'],
            Department::class => ['employees'],
            EmployeePhone::class => ['employee'],
            LocationHolder::class => ['employee', 'organization', 'assets'],
            Organization::class => ['locationHolders'],
            SupplierType::class => ['suppliers'],
            EquipmentRetirementAct::class => ['equipment'],
            LowValueWriteOffAct::class => ['assets'],
            ItemProperty::class => ['asset', 'lowValueMaterial', 'attribute'],
            AttributeDictionary::class => ['itemProperties'],
            ComputerSoftware::class => ['computer', 'license'],
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
