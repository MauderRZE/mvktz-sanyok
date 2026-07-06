<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Models\Location;
use App\Models\Employee;
use App\Models\EquipmentCategory;
use App\Models\Department;
use App\Models\Organization;
use App\Models\BrandTz;

#[Layout('layouts.admin')]
class EquipmentManager extends Component
{
    use WithPagination;

    // Пошук та сортування
    public $search        = '';
    public $sortField     = 'id';
    public $sortDirection = 'desc';

    // Фільтри
    public $filterType         = [];
    public $filterStatus       = [];
    public $filterLocation     = [];
    public $filterEmployee     = [];
    public $filterCategory     = [];
    public $filterDepartment   = [];
    public $filterOrganization = [];
    public $filterBrand        = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search            = '';
        $this->filterType        = [];
        $this->filterStatus      = [];
        $this->filterLocation    = [];
        $this->filterEmployee    = [];
        $this->filterCategory    = [];
        $this->filterDepartment  = [];
        $this->filterOrganization = [];
        $this->filterBrand       = [];
        $this->resetPage();
    }

    public function sortBy($field)
    {
        $this->sortDirection = ($this->sortField === $field)
            ? ($this->sortDirection === 'asc' ? 'desc' : 'asc')
            : 'asc';
        $this->sortField = $field;
    }

    // Дії — делегуємо дочірнім компонентам через dispatch
    public function create()
    {
        $this->dispatch('openEquipmentForm');
    }

    public function edit($id)
    {
        $this->dispatch('editEquipmentForm', id: $id);
    }

    public function view($id)
    {
        $this->dispatch('openEquipmentDetail', id: $id);
    }

    public function delete($id)
    {
        Equipment::findOrFail($id)->delete();
        session()->flash('message', 'Обладнання видалено.');
    }

    // Слухаємо подію від EquipmentForm про збереження
    #[On('equipmentSaved')]
    public function handleEquipmentSaved()
    {
        $this->resetPage();
    }

    public function render()
    {
        $sortMap = [
            'id'           => 'id',
            'inv_number'   => 'inv_number',
            'account_name' => 'account_name',
            'status'       => 'status',
        ];
        $actualSort = $sortMap[$this->sortField] ?? 'id';

        // Полегшений eager-load: ТІЛЬКИ те, що потрібно для рядка таблиці
        $equipments = Equipment::with([
            'assets.componentType',
            'movements.location',
            'movements.employee',
        ])
        ->when($this->search, function ($q) {
            $q->where('inv_number', 'like', '%' . $this->search . '%')
              ->orWhere('account_name', 'like', '%' . $this->search . '%');
        })
        ->when(!empty($this->filterType), function ($q) {
            $q->whereHas('assets', function ($c) {
                $c->whereIn('model_id', $this->filterType);
            });
        })
        ->when(!empty($this->filterStatus), function ($q) {
            $q->whereIn('status', $this->filterStatus);
        })
        ->when(!empty($this->filterCategory), function ($q) {
            $q->whereHas('assets.componentType', function ($c) {
                $c->whereIn('category_id', $this->filterCategory);
            });
        })
        ->when(!empty($this->filterLocation), function ($q) {
            $q->whereHas('assets', function ($c) {
                $c->whereIn('current_loc_id', $this->filterLocation);
            });
        })
        ->when(!empty($this->filterEmployee), function ($q) {
            $q->whereHas('movements', function ($m) {
                $m->whereIn('employee_id', $this->filterEmployee);
            });
        })
        ->when(!empty($this->filterDepartment), function ($q) {
            $q->whereHas('movements.employee', function ($e) {
                $e->whereIn('department_id', $this->filterDepartment);
            });
        })
        ->when(!empty($this->filterOrganization), function ($q) {
            $q->whereHas('assets.holder', function ($h) {
                $h->whereIn('organization_id', $this->filterOrganization);
            });
        })
        ->when(!empty($this->filterBrand), function ($q) {
            $q->whereHas('assets.model', function ($c) {
                $c->whereIn('brand_id', $this->filterBrand);
            });
        })
        ->orderBy($actualSort, $this->sortDirection)
        ->paginate(15);

        return view('livewire.admin.equipment-manager', [
            'equipments'        => $equipments,
            'types'             => EquipmentType::all(),
            'categoriesList'    => EquipmentCategory::all(),
            'locationsList'     => Location::all(),
            'employeesList'     => Employee::all(),
            'departmentsList'   => Department::all(),
            'organizationsList' => Organization::all(),
            'brandsList'        => BrandTz::all(),
        ]);
    }
}
