<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$equipment = \App\Models\Equipment::with([
    'assets.componentType',
    'assets.holder.organization',
    'assets.childAssets.componentType',
    'assets.childAssets.model.brand',
    'assets.childAssets.itemProperties.attribute',
    'movements.employee.department',
    'maintenanceLogs',
    'lowValueMaterials.contract',
    'contract',
    'retirementAct',
])->find(4);

$html = view('livewire.admin.equipment.equipment-detail', [
    'isOpen' => true,
    'equipment' => $equipment
])->render();
file_put_contents('render_output.html', $html);
