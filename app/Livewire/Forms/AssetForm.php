<?php

namespace App\Livewire\Forms;

use App\Models\Asset;
use App\Models\BaseComponent;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AssetForm extends Form
{
    public ?int $assetId = null;

    #[Validate('required|exists:equipment,id')]
    public ?int $equipment_id = null;

    #[Validate('required|exists:base_components,id')]
    public ?int $base_component_id = null;

    #[Validate('nullable|exists:models_tz,id')]
    public ?int $model_id = null;

    #[Validate('nullable|exists:locations,id')]
    public ?int $current_loc_id = null;

    #[Validate('nullable|exists:location_holders,id')]
    public ?int $current_holder_id = null;

    #[Validate('nullable|exists:assets,id')]
    public ?int $parent_asset_id = null;

    #[Validate('nullable|string|max:255')]
    public string $notes = '';

    #[Validate('nullable|string|max:100')]
    public string $serial_number = '';

    #[Validate('boolean')]
    public bool $has_network = false;

    #[Validate('nullable|ip')]
    public string $ip_address = '';

    #[Validate('nullable|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/')]
    public string $mac_address = '';

    #[Validate('nullable|string|max:100')]
    public string $hostname = '';

    #[Validate('nullable|exists:low_value_materials,id')]
    public ?int $nomenclature_id = null;

    #[Validate('nullable|exists:low_value_write_off_acts,id')]
    public ?int $write_off_act_id = null;

    #[Validate('required|string|max:50')]
    public string $status = 'працює';

    public function setAsset(Asset $asset)
    {
        $this->assetId = $asset->id;
        $this->equipment_id = $asset->equipment_id;
        $this->base_component_id = $asset->base_component_id;
        $this->model_id = $asset->model_id;
        $this->current_loc_id = $asset->current_loc_id;
        $this->current_holder_id = $asset->current_holder_id;
        $this->parent_asset_id = $asset->parent_asset_id;
        $this->notes = $asset->notes ?? '';
        $this->serial_number = $asset->serial_number ?? '';

        $this->has_network = ! empty($asset->ip_address) || ! empty($asset->mac_address) || ! empty($asset->hostname);
        $this->ip_address = $asset->ip_address ?? '';
        $this->mac_address = $asset->mac_address ?? '';
        $this->hostname = $asset->hostname ?? '';

        $this->nomenclature_id = $asset->nomenclature_id;
        $this->write_off_act_id = $asset->write_off_act_id;
        $this->status = $asset->status ?? 'працює';
    }

    public function handleBaseComponentChange($value)
    {

        $this->base_component_id = $value ? (int) $value : null;
        if ($value) {
            $component = BaseComponent::find($value);
            if ($component) {
                // Список компонентів, які є самостійними активуми і не можуть підпорядковуватись іншим:
                $allowedComponents = ['системний блок', 'ноутбук'];

                // Назва з бази приводиться до нижнього регістру (наприклад, "Системний блок" -> "системний блок")
                $name = mb_strtolower(trim($component->component_name));

                if (in_array($name, $allowedComponents)) {
                    $this->parent_asset_id = null;
                }
            }
        }
    }

    public function store()
    {
        $this->validate();

        Asset::updateOrCreate(['id' => $this->assetId], [
            'equipment_id' => $this->equipment_id,
            'base_component_id' => $this->base_component_id,
            'model_id' => $this->model_id ?: null,
            'current_loc_id' => $this->current_loc_id ?: null,
            'current_holder_id' => $this->current_holder_id ?: null,
            'parent_asset_id' => $this->parent_asset_id ?: null,
            'notes' => $this->notes ?: null,
            'serial_number' => $this->serial_number ?: null,
            'ip_address' => $this->ip_address ?: null,
            'mac_address' => $this->mac_address ?: null,
            'hostname' => $this->hostname ?: null,
            'nomenclature_id' => $this->nomenclature_id ?: null,
            'write_off_act_id' => $this->write_off_act_id ?: null,
            'status' => $this->status,
        ]);

        $isUpdate = $this->assetId !== null;
        $this->reset();

        return $isUpdate;
    }
}
