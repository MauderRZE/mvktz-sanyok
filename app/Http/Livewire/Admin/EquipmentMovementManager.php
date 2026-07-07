<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\EquipmentMovement;
use App\Models\Equipment;
use App\Models\Location;
use App\Models\Employee;

#[Layout('layouts.admin')]
class EquipmentMovementManager extends Component
{
    public $movements, $movementId, $equip_id, $location_id, $employee_id, $action_date;
    public $equipmentList = [], $locationsList = [], $employeesList = [];
    public $isOpen = 0;

    public function render()
    {
        $this->movements = EquipmentMovement::with([
            'equipment',
            'employee',
            'asset.location',
            'toHolder.organization'
        ])->get();
        $this->equipmentList = Equipment::all();
        $this->locationsList = Location::all();
        $this->employeesList = Employee::all();
        return view('livewire.admin.equipment-movement-manager');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->action_date = date('Y-m-d');
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields(){
        $this->movementId = null;
        $this->equip_id = null;
        $this->location_id = null;
        $this->employee_id = null;
        $this->action_date = '';
    }

    public function store()
    {
        $this->validate([
            'equip_id' => 'required|exists:equipment,id',
            'location_id' => 'required|exists:locations,id',
            'employee_id' => 'nullable|exists:employee,id',
            'action_date' => 'required|date',
        ]);

        // Знаходимо або створюємо LocationHolder для цільового співробітника
        $toHolder = \App\Models\LocationHolder::firstOrCreate([
            'employee_id' => $this->employee_id ?: null,
            'organization_id' => null,
        ]);

        // Отримуємо обладнання та його перший компонент
        $equipment = Equipment::findOrFail($this->equip_id);
        $asset = $equipment->assets()->first();
        $asset_id = $asset ? $asset->id : null;

        // Попередній утримувач (from_holder_id)
        $from_holder_id = $asset ? $asset->current_holder_id : null;

        // Оновлюємо поточне розташування та утримувача для комплектуючих обладнання
        if ($asset) {
            $equipment->assets()->update([
                'current_loc_id' => $this->location_id,
                'current_holder_id' => $toHolder->id,
            ]);
        }

        // Створюємо або оновлюємо запис переміщення
        EquipmentMovement::updateOrCreate(['id' => $this->movementId], [
            'equip_id' => $this->equip_id,
            'asset_id' => $asset_id,
            'from_holder_id' => $from_holder_id,
            'to_holder_id' => $toHolder->id,
            'employee_id' => $this->employee_id ?: null,
            'action_date' => $this->action_date,
        ]);

        session()->flash('message', 
            $this->movementId ? 'Запис про рух оновлено.' : 'Переміщення зареєстровано.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $move = EquipmentMovement::findOrFail($id);
        $this->movementId = $id;
        $this->equip_id = $move->equip_id;
        $this->employee_id = $move->employee_id;
        $this->action_date = $move->action_date;
        $this->location_id = $move->asset ? $move->asset->current_loc_id : null;
        $this->openModal();
    }

    public function delete($id)
    {
        EquipmentMovement::find($id)->delete();
        session()->flash('message', 'Запис про переміщення видалено.');
    }
}
