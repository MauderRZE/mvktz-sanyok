<?php

namespace App\Livewire\Forms;

use App\Models\Asset;
use App\Models\EquipmentMovement;
use App\Models\LocationHolder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

class MovementForm extends Form
{
    public ?int $movementId = null;

    #[Validate('required|exists:assets,id')]
    public ?int $asset_id = null;

    #[Validate('required|exists:locations,id')]
    public ?int $location_id = null;

    #[Validate('nullable|exists:employee,id')]
    public ?int $employee_id = null;

    #[Validate('required|date')]
    public string $action_date = '';

    public function setMovement(EquipmentMovement $move)
    {
        $this->movementId = $move->id;
        $this->asset_id = $move->asset_id;
        $this->employee_id = $move->employee_id;
        if ($move->action_date) {
            $this->action_date = (is_string($move->action_date))
                ? substr($move->action_date, 0, 10)
                : $move->action_date->format('Y-m-d');
        }
        $this->location_id = $move->location_id ?: ($move->asset ? $move->asset->current_loc_id : null);
    }

    public function store()
    {
        $this->validate();

        return DB::transaction(function () {
            // Знаходимо або створюємо LocationHolder для цільового співробітника
            $toHolder = LocationHolder::firstOrCreate([
                'employee_id' => $this->employee_id ?: null,
                'organization_id' => null,
            ]);

            // Отримуємо актив
            $asset = Asset::findOrFail($this->asset_id);

            // Попередній утримувач (from_holder_id)
            $from_holder_id = $asset->current_holder_id;

            // Оновлюємо поточне розташування та утримувача для активу
            $asset->update([
                'current_loc_id' => $this->location_id,
                'current_holder_id' => $toHolder->id,
            ]);

            // Створюємо або оновлюємо запис переміщення
            EquipmentMovement::updateOrCreate(['id' => $this->movementId], [
                'equip_id' => $asset->equipment_id,
                'asset_id' => $asset->id,
                'location_id' => $this->location_id,
                'from_holder_id' => $from_holder_id,
                'to_holder_id' => $toHolder->id,
                'employee_id' => $this->employee_id ?: null,
                'action_date' => $this->action_date,
            ]);

            $isUpdate = $this->movementId !== null;
            $this->reset();

            return $isUpdate;
        });
    }
}
