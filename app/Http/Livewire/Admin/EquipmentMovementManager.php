<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\EquipmentMovement;
use App\Models\Equipment;
use App\Models\Location;
use App\Models\Employee;
use App\Models\Asset;

#[Layout('layouts.admin')]
class EquipmentMovementManager extends Component
{
    public $movements, $movementId, $asset_id, $location_id, $employee_id, $action_date;
    public $assetsList = [], $locationsList = [], $employeesList = [];
    public $isOpen = false;

    public $search = '';
    public $filterAsset = [];
    public $filterLocation = [];
    public $filterEmployee = [];

    public function render()
    {
        $query = EquipmentMovement::with([
            'asset.equipment',
            'asset.model.brand',
            'asset.baseComponent',
            'employee',
            'asset.location',
            'toHolder.organization'
        ])
        ->when($this->search, function($q) {
            $search = '%' . $this->search . '%';
            $q->where(function($sub) use ($search) {
                $sub->whereHas('asset.equipment', function($eq) use ($search) {
                    $eq->where('inv_number', 'like', $search)
                       ->orWhere('account_name', 'like', $search);
                })
                ->orWhereHas('asset.model', function($m) use ($search) {
                    $m->where('model_name', 'like', $search);
                })
                ->orWhereHas('asset.baseComponent', function($c) use ($search) {
                    $c->where('component_name', 'like', $search);
                })
                ->orWhereHas('asset', function($a) use ($search) {
                    $a->where('serial_number', 'like', $search);
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
        ->when(!empty($this->filterAsset), function($q) {
            $q->whereIn('asset_id', $this->filterAsset);
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
        $this->assetsList = Asset::with(['model.brand', 'equipment', 'baseComponent'])->get();
        $this->locationsList = Location::all();
        $this->employeesList = Employee::all();
        return view('livewire.admin.equipment-movement-manager');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterAsset = [];
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
        $this->asset_id = null;
        $this->location_id = null;
        $this->employee_id = null;
        $this->action_date = '';
    }

    public function store()
    {
        $this->validate([
            'asset_id' => 'required|exists:assets,id',
            'location_id' => 'required|exists:locations,id',
            'employee_id' => 'nullable|exists:employee,id',
            'action_date' => 'required|date',
        ]);

        // Знаходимо або створюємо LocationHolder для цільового співробітника
        $toHolder = \App\Models\LocationHolder::firstOrCreate([
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
        $this->asset_id = $move->asset_id;
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
