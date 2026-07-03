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

$missing = [];
foreach ($models as $class => $table) {
    if (!class_exists("App\\Models\\$class")) continue;
    $model = app("App\\Models\\$class");
    $fillable = $model->getFillable();
    
    // If empty fillable but not guarded, or uses guarded instead
    if (empty($fillable)) continue;
    
    // SystemError uses a different connection, might need to specify Schema::connection('sqlite_errors')
    $connection = $model->getConnectionName();
    $columns = \Schema::connection($connection)->getColumnListing($table);
    
    $ignore = ['id', 'created_at', 'updated_at', 'deleted_at', 'remember_token', 'email_verified_at'];
    $expected = array_diff($columns, $ignore);
    $diff = array_diff($expected, $fillable);
    
    if (!empty($diff)) {
        $missing[$class] = array_values($diff);
    }
}
echo json_encode($missing, JSON_PRETTY_PRINT);
