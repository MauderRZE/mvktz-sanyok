<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Models\Location;
use App\Models\Employee;
use App\Models\EquipmentCategory;

class EquipmentManager extends Component
{
    use \Livewire\WithPagination;

    public $equipmentId, $inventory_number, $accounting_name, $equipment_type_id, $status, $commissioning_date;
    public $types;
    public $isOpen = 0;
    
    public $isViewOpen = false;
    public $viewEquipmentId = null;
    public $viewEquipment = null;

    // Filters & Sorting
    public $search = '';
    public $filterType = [];
    public $filterStatus = [];
    public $filterLocation = [];
    public $filterEmployee = [];
    public $filterCategory = [];
    public $sortField = 'id';
    public $sortDirection = 'desc';

    public function updating()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterType = [];
        $this->filterStatus = [];
        $this->filterLocation = [];
        $this->filterEmployee = [];
        $this->filterCategory = [];
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $this->types = EquipmentType::all();
        $categoriesList = EquipmentCategory::all();
        $locationsList = Location::all();
        $employeesList = Employee::all();
        
        $sortMap = [
            'id' => 'id',
            'inventory_number' => 'inv_number',
            'accounting_name' => 'account_name',
            'status' => 'status',
        ];
        $actualSortField = $sortMap[$this->sortField] ?? 'id';

        $query = Equipment::with(['type', 'components.componentType', 'movements.location', 'movements.employee', 'complaints', 'maintenanceLogs', 'softwareLicenses'])
            ->when($this->search, function($q) {
                $q->where('inv_number', 'like', '%' . $this->search . '%')
                  ->orWhere('account_name', 'like', '%' . $this->search . '%');
            })
            ->when(!empty($this->filterType), function($q) {
                $q->whereHas('components', function($c) {
                    $c->whereIn('model_id', $this->filterType);
                });
            })
            ->when(!empty($this->filterStatus), function($q) {
                $q->whereIn('status', $this->filterStatus);
            })
            ->when(!empty($this->filterCategory), function($q) {
                $q->whereHas('components.componentType', function($c) {
                    $c->whereIn('category_id', $this->filterCategory);
                });
            })
            ->when(!empty($this->filterLocation), function($q) {
                $q->whereHas('components', function($c) {
                    $c->whereIn('current_loc_id', $this->filterLocation);
                });
            })
            ->when(!empty($this->filterEmployee), function($q) {
                $q->whereHas('movements', function($m) {
                    $m->whereIn('employee_id', $this->filterEmployee);
                });
            })
            ->orderBy($actualSortField, $this->sortDirection);

        return view('livewire.admin.equipment-manager', [
            'equipments' => $query->paginate(15),
            'categoriesList' => $categoriesList,
            'locationsList' => $locationsList,
            'employeesList' => $employeesList,
        ])->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInputFields();
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
        $this->equipmentId = null;
        $this->inventory_number = '';
        $this->accounting_name = '';
        $this->equipment_type_id = '';
        $this->status = 'В експлуатації';
        $this->commissioning_date = '';
    }

    public function store()
    {
        $this->validate([
            'inventory_number' => 'required',
            'accounting_name' => 'required',
            'equipment_type_id' => 'required|integer',
            'status' => 'required',
            'commissioning_date' => 'required|date',
        ]);

        Equipment::updateOrCreate(['id' => $this->equipmentId], [
            'inventory_number' => $this->inventory_number,
            'accounting_name' => $this->accounting_name,
            'equipment_type_id' => $this->equipment_type_id,
            'status' => $this->status,
            'commissioning_date' => $this->commissioning_date
        ]);

        session()->flash('message', 
            $this->equipmentId ? 'Обладнання оновлено.' : 'Обладнання створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $eq = Equipment::findOrFail($id);
        $this->equipmentId = $id;
        $this->inventory_number = $eq->inventory_number;
        $this->accounting_name = $eq->accounting_name;
        $this->equipment_type_id = $eq->equipment_type_id;
        $this->status = $eq->status;
        $this->commissioning_date = $eq->commissioning_date;
        $this->openModal();
    }

    public function view($id)
    {
        $this->viewEquipmentId = $id;
        $this->viewEquipment = Equipment::with(['type', 'components.componentType', 'movements.location', 'movements.employee', 'complaints', 'maintenanceLogs', 'softwareLicenses', 'lowValueMaterials.material'])->findOrFail($id);
        $this->isViewOpen = true;
    }

    public function closeView()
    {
        $this->isViewOpen = false;
        $this->viewEquipmentId = null;
        $this->viewEquipment = null;
    }

    public function delete($id)
    {
        Equipment::find($id)->delete();
        session()->flash('message', 'Обладнання видалено.');
    }
}
