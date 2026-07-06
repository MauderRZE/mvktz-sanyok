<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$equipment = App\Models\Equipment::with([
    'assets.componentType',
    'assets.childAssets.componentType',
    'assets.childAssets.model.brand',
    'assets.childAssets.itemProperties.attribute'
])->find(1);

$assets = $equipment->assets->whereNull('parent_asset_id');
echo "Found " . $assets->count() . " top level assets.\n";

foreach ($assets as $asset) {
    echo "- " . ($asset->componentType->component_name ?? 'Unknown') . "\n";
    if ($asset->childAssets->count() > 0) {
        echo "  Has " . $asset->childAssets->count() . " children:\n";
        foreach ($asset->childAssets as $child) {
            echo "  - " . ($child->componentType->component_name ?? 'Unknown') . "\n";
        }
    }
}
