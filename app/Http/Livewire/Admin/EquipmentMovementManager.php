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
    public $isOpen = false;

    public $search = '';
    public $filterEquipment = [];
    public $filterLocation = [];
    public $filterEmployee = [];

    public function render()
    {
        $query = EquipmentMovement::with([
            'equipment',
            'employee',
            'asset.location',
            'toHolder.organization'
        ])
        ->when($this->search, function($q) {
            $search = '%' . $this->search . '%';
            $q->where(function($sub) use ($search) {
                $sub->whereHas('equipment', function($eq) use ($search) {
                    $eq->where('inv_number', 'like', $search)
                       ->orWhere('account_name', 'like', $search);
                })
                ->orWhereHas('employee', function($emp) use ($search) {
                    $emp->where('last_name', 'like', $search)
                        ->orWhere('first_name', 'like', $search)
                        ->orWhere('middle_name', 'like', $search);
                })
                ->orWhereHas('asset.location', function($loc) use ($search) {
                    $loc->where('room_number', 'like', $search);
                });
            });
        })
        ->when(!empty($this->filterEquipment), function($q) {
            $q->whereIn('equip_id', $this->filterEquipment);
        })
        ->when(!empty($this->filterLocation), function($q) {
            $q->whereHas('asset', function($a) {
                $a->whereIn('current_loc_id', $this->filterLocation);
            });
        })
        ->when(!empty($this->filterEmployee), function($q) {
            $q->whereIn('employee_id', $this->filterEmployee);
        })
        ->orderBy('action_date', 'desc');

        $this->movements = $query->get();
        $this->equipmentList = Equipment::all();
        $this->locationsList = Location::all();
        $this->employeesList = Employee::all();
        return view('livewire.admin.equipment-movement-manager');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterEquipment = [];
        $this->filterLocation = [];
        $this->filterEmployee = [];
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
