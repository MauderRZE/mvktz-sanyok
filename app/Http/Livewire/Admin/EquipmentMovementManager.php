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
            'employee_id' => 'nullable|exists:employees,id',
            'move_date' => 'required|date',
        ]);

        EquipmentMovement::updateOrCreate(['id' => $this->movementId], [
            'equipment_id' => $this->equipment_id,
            'location_id' => $this->location_id,
            'employee_id' => $this->employee_id ?: null,
            'move_date' => $this->move_date,
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
