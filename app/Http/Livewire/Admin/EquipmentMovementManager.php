<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\EquipmentMovement;
use App\Models\Equipment;
use App\Models\Location;
use App\Models\Employee;
use App\Models\Asset;
use App\Livewire\Forms\MovementForm;

#[Layout('layouts.admin')]
class EquipmentMovementManager extends Component
{
    public MovementForm $form;
    
    public $movements;
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
        $this->form->reset();
        $this->form->action_date = date('Y-m-d');
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

    public function store()
    {
        $isUpdate = $this->form->store();

        session()->flash('message', 
            $isUpdate ? 'Запис про рух оновлено.' : 'Переміщення зареєстровано.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $move = EquipmentMovement::findOrFail($id);
        $this->form->setMovement($move);
        $this->openModal();
    }

    public function delete($id)
    {
        EquipmentMovement::find($id)->delete();
        session()->flash('message', 'Запис про переміщення видалено.');
    }
}
