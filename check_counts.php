<?php
$models = [
    \App\Models\AttributeDictionary::class,
    \App\Models\ComputerSoftware::class,
    \App\Models\EmployeePhone::class,
    \App\Models\ItemProperty::class,
    \App\Models\SupplierType::class
];

foreach ($models as $model) {
    try {
        $count = $model::count();
        echo class_basename($model) . " count: " . $count . "\n";
    } catch (\Exception $e) {
        echo class_basename($model) . " table missing or error\n";
    }
}
