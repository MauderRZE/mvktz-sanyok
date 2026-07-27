<?php

namespace App\Http\Livewire\Admin;

use App\Livewire\Forms\MovementForm;
use App\Models\Asset;
use App\Models\Employee;
use App\Models\EquipmentMovement;
use App\Models\Location;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class EquipmentMovementManager extends Component
{
    use WithPagination;

    public MovementForm $form;

    public $isOpen = false;

    public $search = '';

    public $filterAsset = [];

    public $filterLocation = [];

    public $filterEmployee = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterAsset()
    {
        $this->resetPage();
    }

    public function updatingFilterLocation()
    {
        $this->resetPage();
    }

    public function updatingFilterEmployee()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = EquipmentMovement::with([
            'asset:id,equipment_id,model_id,base_component_id,serial_number,current_loc_id',
            'asset.equipment:id,inv_number,account_name',
            'asset.model:id,model_name,brand_id',
            'asset.model.brand:id,brandtz_name',
            'asset.baseComponent:id,component_name',
            'asset.location:id,room_number',
            'employee:id,first_name,last_name,middle_name',
            'location:id,room_number',
            'toHolder:id,organization_id',
            'toHolder.organization:id,org_name',
        ])
            ->when($this->search, function ($q) {
                $search = '%'.$this->search.'%';
                $q->where(function ($sub) use ($search) {
                    $sub->whereHas('asset.equipment', function ($eq) use ($search) {
                        $eq->where('inv_number', 'like', $search)
                            ->orWhere('account_name', 'like', $search);
                    })
                        ->orWhereHas('asset.model', function ($m) use ($search) {
                            $m->where('model_name', 'like', $search);
                        })
                        ->orWhereHas('asset.baseComponent', function ($c) use ($search) {
                            $c->where('component_name', 'like', $search);
                        })
                        ->orWhereHas('asset', function ($a) use ($search) {
                            $a->where('serial_number', 'like', $search);
                        })
                        ->orWhereHas('employee', function ($emp) use ($search) {
                            $emp->where('last_name', 'like', $search)
                                ->orWhere('first_name', 'like', $search)
                                ->orWhere('middle_name', 'like', $search);
                        })
                        ->orWhereHas('location', function ($loc) use ($search) {
                            $loc->where('room_number', 'like', $search);
                        })
                        ->orWhereHas('asset.location', function ($loc) use ($search) {
                            $loc->where('room_number', 'like', $search);
                        });
                });
            })
            ->when(! empty($this->filterAsset), function ($q) {
                $q->whereIn('asset_id', $this->filterAsset);
            })
            ->when(! empty($this->filterLocation), function ($q) {
                $q->where(function ($sub) {
                    $sub->whereIn('location_id', $this->filterLocation)
                        ->orWhereHas('asset', function ($a) {
                            $a->whereIn('current_loc_id', $this->filterLocation);
                        });
                });
            })
            ->when(! empty($this->filterEmployee), function ($q) {
                $q->whereIn('employee_id', $this->filterEmployee);
            })
            ->orderBy('action_date', 'desc')
            ->orderBy('id', 'desc');

        $movements = $query->paginate(25);

        $assetsList = Asset::select('id', 'model_id', 'equipment_id', 'base_component_id', 'serial_number')
            ->with([
                'baseComponent:id,component_name',
                'model:id,model_name',
                'equipment:id,inv_number',
            ])
            ->get();

        $locationsList = Location::select('id', 'room_number')->orderBy('room_number')->get();
        $employeesList = Employee::select('id', 'first_name', 'last_name', 'middle_name', 'position')->orderBy('last_name')->get();

        return view('livewire.admin.equipment-movement-manager', compact(
            'movements',
            'assetsList',
            'locationsList',
            'employeesList'
        ));
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterAsset = [];
        $this->filterLocation = [];
        $this->filterEmployee = [];
        $this->resetPage();
    }

    public function create()
    {
        $this->form->reset();
        $this->form->action_date = date('Y-m-d\TH:i:s');
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
        EquipmentMovement::where('id', $id)->delete();
        session()->flash('message', 'Запис про переміщення видалено.');
    }
}
