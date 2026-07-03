<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$models = [
    'BaseComponent' => 'base_components',
    'BaseMaterial' => 'base_materials',
    'Contract' => 'contracts',
    'Employee' => 'employees',
    'Equipment' => 'equipment',
    'EquipmentCategory' => 'equipment_categories',
    'EquipmentComplaint' => 'equipment_complaints',
    'EquipmentComponent' => 'equipment_components',
    'EquipmentMovement' => 'equipment_movement',
    'EquipmentType' => 'equipment_types',
    'Location' => 'locations',
    'LowValueMaterial' => 'low_value_materials',
    'MaintenanceLog' => 'maintenance_log',
    'MaintenanceType' => 'maintenance_types',
    'SoftwareLicense' => 'software_licenses',
    'Supplier' => 'suppliers',
    'SystemError' => 'system_errors',
    'TypeRequirement' => 'type_requirements',
    'User' => 'users',
];

$missingBelongsTo = [];
$missingHasMany = [];

foreach ($models as $class => $table) {
    if (!class_exists("App\\Models\\$class")) continue;
    
    $modelClass = "App\\Models\\$class";
    $columns = \Schema::getColumnListing($table);
    
    foreach ($columns as $column) {
        if (preg_match('/^(.*)_id$/', $column, $matches)) {
            $relationName = \Illuminate\Support\Str::camel($matches[1]);
            
            if (!method_exists($modelClass, $relationName)) {
                $missingBelongsTo[$class][] = $relationName . " (поле $column)";
            }
            
            // Expected model for the related table
            // This is a bit naive but should catch most conventions
            $relatedModelNames = [
                \Illuminate\Support\Str::studly($matches[1]),
                \Illuminate\Support\Str::studly(str_replace('parent_', '', $matches[1]))
            ];
            
            $relatedClassName = null;
            foreach ($relatedModelNames as $name) {
                 if (class_exists("App\\Models\\$name")) {
                      $relatedClassName = $name;
                      break;
                 }
            }
            
            // Check reverse (hasMany / hasOne)
            if ($relatedClassName) {
                $reverseRelationPlural = \Illuminate\Support\Str::camel(\Illuminate\Support\Str::plural(class_basename($class)));
                $reverseRelationSingular = \Illuminate\Support\Str::camel(class_basename($class));
                
                if (!method_exists("App\\Models\\$relatedClassName", $reverseRelationPlural) && 
                    !method_exists("App\\Models\\$relatedClassName", $reverseRelationSingular)) {
                    $missingHasMany[$relatedClassName][] = "$reverseRelationPlural / $reverseRelationSingular (для $table.$column)";
                }
            }
        }
    }
}

echo json_encode([
    'belongsTo' => $missingBelongsTo,
    'hasMany' => $missingHasMany
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
