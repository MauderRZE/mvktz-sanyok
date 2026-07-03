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
    public $movements, $movementId, $equipment_id, $location_id, $employee_id, $move_date;
    public $equipmentList = [], $locationsList = [], $employeesList = [];
    public $isOpen = 0;

    public function render()
    {
        $this->movements = EquipmentMovement::with(['equipment', 'location', 'employee'])->get();
        $this->equipmentList = Equipment::all();
        $this->locationsList = Location::all();
        $this->employeesList = Employee::all();
        return view('livewire.admin.equipment-movement-manager');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->move_date = date('Y-m-d');
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
        $this->equipment_id = null;
        $this->location_id = null;
        $this->employee_id = null;
        $this->move_date = '';
    }

    public function store()
    {
        $this->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'location_id' => 'required|exists:locations,id',
            'employee_id' => 'nullable|exists:employee,id',
            'move_date' => 'required|date',
        ]);

        // Знаходимо або створюємо LocationHolder для цільового співробітника
        $toHolder = \App\Models\LocationHolder::firstOrCreate([
            'employee_id' => $this->employee_id ?: null,
            'organization_id' => null,
        ]);

        // Отримуємо обладнання та його перший компонент
        $equipment = Equipment::findOrFail($this->equipment_id);
        $asset = $equipment->components()->first();
        $asset_id = $asset ? $asset->id : null;

        // Попередній утримувач (from_holder_id)
        $from_holder_id = $asset ? $asset->current_holder_id : null;

        // Оновлюємо поточне розташування та утримувача для комплектуючих обладнання
        if ($asset) {
            $equipment->components()->update([
                'current_loc_id' => $this->location_id,
                'current_holder_id' => $toHolder->id,
            ]);
        }

        // Створюємо або оновлюємо запис переміщення
        EquipmentMovement::updateOrCreate(['id' => $this->movementId], [
            'equip_id' => $this->equipment_id,
            'asset_id' => $asset_id,
            'from_holder_id' => $from_holder_id,
            'to_holder_id' => $toHolder->id,
            'employee_id' => $this->employee_id ?: null,
            'action_date' => $this->move_date,
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
        $this->equipment_id = $move->equipment_id;
        $this->location_id = $move->location_id;
        $this->employee_id = $move->employee_id;
        $this->move_date = $move->move_date;
        $this->openModal();
    }

    public function delete($id)
    {
        EquipmentMovement::find($id)->delete();
        session()->flash('message', 'Запис про переміщення видалено.');
    }
}
