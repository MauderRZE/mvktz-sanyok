<?php

namespace App\Livewire\Forms;

use App\Models\Asset;
use App\Models\BaseComponent;
use App\Models\Equipment;
use App\Models\EquipmentMovement;
use App\Models\LocationHolder;
use App\Models\LowValueMaterial;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AssetForm extends Form
{
    public ?int $assetId = null;

    // Зверніть увагу: прибрали #[Validate] з $equipment_id, бо воно тепер у rules()
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

    #[Validate('nullable|integer|min:1900|max:2100')]
    public ?int $purchase_year = null;

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

    // --- НОВІ ПОЛЯ ДЛЯ EQUIPMENT "НА ЛЬОТУ" ---
    public bool $create_new_equipment = false;

    public ?string $new_inv_number = null;
    public ?string $new_account_name = null;
    public ?float $new_buy_price = null;

    public function rules(): array
    {
        $rules = [
            'base_component_id' => 'required',
            // Інші ваші базові правила валідації...
        ];

        if ($this->create_new_equipment) {
            $rules['new_inv_number'] = 'required|string|max:255';
            $rules['new_account_name'] = 'nullable|string|max:255';
            $rules['new_buy_price'] = 'nullable|numeric|min:0';
        } else {
            $rules['equipment_id'] = 'nullable|exists:equipment,id';
        }

        return $rules;
    }

    public function setAsset(Asset $asset)
    {
        $this->assetId = $asset->id;
        $this->equipment_id = ($asset->parent_asset_id && $asset->parent?->equipment_id)
        ? $asset->parent->equipment_id
        : $asset->equipment_id;
        $this->base_component_id = $asset->base_component_id;
        $this->model_id = $asset->model_id;
        $this->current_loc_id = $asset->current_loc_id;
        $this->current_holder_id = $asset->current_holder_id;
        $this->parent_asset_id = $asset->parent_asset_id;
        $this->notes = $asset->notes ?? '';
        $this->serial_number = $asset->serial_number ?? '';
        $this->purchase_year = $asset->purchase_year;

        $this->has_network = !empty($asset->ip_address) || !empty($asset->mac_address) || !empty($asset->hostname);
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

    // public function store()
    // {
    //     $this->validate();
    //     $isUpdate = $this->assetId !== null;

    //     DB::transaction(function () {
    //         $equipmentId = $this->equipment_id;

    //         // Якщо увімкнено створення нового обладнання "на льоту"
    //         if ($this->create_new_equipment) {
    //             $equipment = Equipment::create([
    //                 'inv_number'   => $this->new_inv_number,
    //                 'account_name' => $this->new_account_name ?: null,
    //                 'buy_price'    => $this->new_buy_price ?: null,
    //                 'status'       => 'в експлуатації', // Або за замовчуванням
    //             ]);

    //             $equipmentId = $equipment->id;
    //         }

    //         // Зберігаємо / оновлюємо актив
    //         Asset::updateOrCreate(['id' => $this->assetId], [
    //             'equipment_id'      => $equipmentId,
    //             'base_component_id' => $this->base_component_id,
    //             'model_id'          => $this->model_id ?: null,
    //             'current_loc_id'    => $this->current_loc_id ?: null,
    //             'current_holder_id' => $this->current_holder_id ?: null,
    //             'parent_asset_id'   => $this->parent_asset_id ?: null,
    //             'notes'             => $this->notes ?: null,
    //             'serial_number'     => $this->serial_number ?: null,
    //             'purchase_year'     => $this->purchase_year ?: null,
    //             'ip_address'        => $this->ip_address ?: null,
    //             'mac_address'       => $this->mac_address ?: null,
    //             'hostname'          => $this->hostname ?: null,
    //             'nomenclature_id'   => $this->nomenclature_id ?: null,
    //             'write_off_act_id'  => $this->write_off_act_id ?: null,
    //             'status'            => $this->status,
    //         ]);
    //     });
    //     $isUpdate = $this->assetId !== null;
    //     $this->reset();

    //     return $isUpdate;
    // }
    public function store(): bool
    {
        $this->validate();
        $isUpdate = $this->assetId !== null;

        DB::transaction(function () use ($isUpdate) {
            $equipmentId = $this->equipment_id;

            // Якщо обрано системний блок — обладнання автоматично береться від нього!
            if (!empty($this->parent_asset_id)) {
                $parent = Asset::find($this->parent_asset_id);
                if ($parent && !empty($parent->equipment_id)) {
                    $equipmentId = $parent->equipment_id;
                }
            }

            // 1. Створення нового обладнання "на льоту" (якщо вибрано чекбокс)
            if ($this->create_new_equipment) {
                $equipment = Equipment::create([
                    'inv_number' => $this->new_inv_number,
                    'account_name' => $this->new_account_name ?: null,
                    'buy_price' => $this->new_buy_price ?: null,
                    'status' => 'в експлуатації',
                ]);

                $equipmentId = $equipment->id;
            }

            $existingAsset = $this->assetId ? Asset::lockForUpdate()->find($this->assetId) : null;
            $wasWrittenOff = $existingAsset && !empty($existingAsset->write_off_act_id);
            $nowWrittenOff = !empty($this->write_off_act_id);

            // Якщо обрано Акт списання — автоматично ставимо статус 'списано'
            $finalStatus = $nowWrittenOff ? 'списано' : $this->status;

            // 2. Зберігаємо або оновлюємо сам Asset
            $asset = Asset::updateOrCreate(['id' => $this->assetId], [
                'equipment_id' => $equipmentId,
                'base_component_id' => $this->base_component_id,
                'model_id' => $this->model_id ?: null,
                'current_loc_id' => $this->current_loc_id ?: null,
                'current_holder_id' => $this->current_holder_id ?: null,
                'parent_asset_id' => $this->parent_asset_id ?: null,
                'notes' => $this->notes ?: null,
                'serial_number' => $this->serial_number ?: null,
                'purchase_year' => $this->purchase_year ?: null,
                'ip_address' => $this->ip_address ?: null,
                'mac_address' => $this->mac_address ?: null,
                'hostname' => $this->hostname ?: null,
                'nomenclature_id' => $this->nomenclature_id ?: null,
                'write_off_act_id' => $this->write_off_act_id ?: null,
                'status' => $finalStatus,
            ]);

            // 3. Первинна видача нового активу (створення нового рядка в Asset)
            if (!$isUpdate) {
                // Зменшуємо залишок на складі в партії LowValueMaterial
                if ($this->nomenclature_id) {
                    LowValueMaterial::where('id', $this->nomenclature_id)
                        ->where('count', '>', 0)
                        ->decrement('count', 1);
                }

                // Фіксуємо передачу людині / встановлення в ПК в історії
                EquipmentMovement::create([
                    'equip_id' => $asset->equipment_id,
                    'asset_id' => $asset->id,
                    'location_id' => $asset->current_loc_id,
                    'from_holder_id' => null,
                    'to_holder_id' => $asset->current_holder_id,
                    'employee_id' => $asset->current_holder_id ? LocationHolder::find($asset->current_holder_id)?->employee_id : null,
                    'action_date' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
            }

            // 4. Списання за Актом (коли щойно обрали Акт у селекті)
            if (!$wasWrittenOff && $nowWrittenOff) {
                EquipmentMovement::create([
                    'equip_id' => $asset->equipment_id,
                    'asset_id' => $asset->id,
                    'location_id' => $asset->current_loc_id,
                    'from_holder_id' => $asset->current_holder_id,
                    'to_holder_id' => null,
                    'employee_id' => null,
                    'action_date' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
            }
        });

        $this->reset();

        return $isUpdate;
    }
}
