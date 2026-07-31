<?php

namespace App\Http\Livewire\Admin;

use App\Models\BrandTz;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Models\Location;
use App\Models\Organization;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class EquipmentManager extends Component
{
    use WithPagination;

    // Пошук та сортування
    public $search = '';

    public $sortField = 'id';

    public $sortDirection = 'desc';

    // Фільтри
    public $filterType = [];

    public $filterStatus = [];

    public $filterLocation = [];

    public $filterEmployee = [];

    public $filterCategory = [];

    public $filterDepartment = [];

    public $filterOrganization = [];

    public $filterBrand = [];

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
        $this->search = '';
        $this->filterType = [];
        $this->filterStatus = [];
        $this->filterLocation = [];
        $this->filterEmployee = [];
        $this->filterCategory = [];
        $this->filterDepartment = [];
        $this->filterOrganization = [];
        $this->filterBrand = [];
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
            'id' => 'id',
            'inv_number' => 'inv_number',
            'account_name' => 'account_name',
            'status' => 'status',
            'components_count' => 'assets_count',
        ];
        $actualSort = $sortMap[$this->sortField] ?? 'id';

        // Полегшений eager-load: ТІЛЬКИ те, що потрібно для рядка таблиці
        $equipments = Equipment::with([
            'assets.componentType',
            'movements.asset.location',
            'movements.employee',
        ])
            ->withCount('assets')
            ->when($this->search, function ($q) {
                $q->where('inv_number', 'like', '%'.$this->search.'%')
                    ->orWhere('account_name', 'like', '%'.$this->search.'%');
            })
            ->when(! empty($this->filterType), function ($q) {
                $hasNull = in_array('null', $this->filterType, true) || in_array(null, $this->filterType, true);
                $values = array_filter($this->filterType, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($values, $hasNull) {
                    if (! empty($values)) {
                        $sub->whereHas('assets', function ($c) use ($values) {
                            $c->whereIn('model_id', $values);
                        });
                    }
                    if ($hasNull) {
                        $sub->orWhereDoesntHave('assets')
                            ->orWhereHas('assets', function ($c) {
                                $c->whereNull('model_id');
                            });
                    }
                });
            })
            ->when(! empty($this->filterStatus), function ($q) {
                $hasNull = in_array('null', $this->filterStatus, true) || in_array(null, $this->filterStatus, true);
                $values = array_filter($this->filterStatus, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($values, $hasNull) {
                    if (! empty($values)) {
                        $sub->whereIn('status', $values);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('status');
                    }
                });
            })
            ->when(! empty($this->filterCategory), function ($q) {
                $hasNull = in_array('null', $this->filterCategory, true) || in_array(null, $this->filterCategory, true);
                $values = array_filter($this->filterCategory, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($values, $hasNull) {
                    if (! empty($values)) {
                        $sub->whereHas('assets.componentType', function ($c) use ($values) {
                            $c->whereIn('category_id', $values);
                        });
                    }
                    if ($hasNull) {
                        $sub->orWhereDoesntHave('assets.componentType')
                            ->orWhereHas('assets.componentType', function ($c) {
                                $c->whereNull('category_id');
                            });
                    }
                });
            })
            ->when(! empty($this->filterLocation), function ($q) {
                $hasNull = in_array('null', $this->filterLocation, true) || in_array(null, $this->filterLocation, true);
                $values = array_filter($this->filterLocation, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($values, $hasNull) {
                    if (! empty($values)) {
                        $sub->whereHas('assets', function ($c) use ($values) {
                            $c->whereIn('current_loc_id', $values);
                        });
                    }
                    if ($hasNull) {
                        $sub->orWhereDoesntHave('assets')
                            ->orWhereHas('assets', function ($c) {
                                $c->whereNull('current_loc_id');
                            });
                    }
                });
            })
            ->when(! empty($this->filterEmployee), function ($q) {
                $hasNull = in_array('null', $this->filterEmployee, true) || in_array(null, $this->filterEmployee, true);
                $values = array_filter($this->filterEmployee, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($values, $hasNull) {
                    if (! empty($values)) {
                        $sub->whereHas('movements', function ($m) use ($values) {
                            $m->whereIn('employee_id', $values);
                        });
                    }
                    if ($hasNull) {
                        $sub->orWhereDoesntHave('movements')
                            ->orWhereHas('movements', function ($m) {
                                $m->whereNull('employee_id');
                            });
                    }
                });
            })
            ->when(! empty($this->filterDepartment), function ($q) {
                $hasNull = in_array('null', $this->filterDepartment, true) || in_array(null, $this->filterDepartment, true);
                $values = array_filter($this->filterDepartment, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($values, $hasNull) {
                    if (! empty($values)) {
                        $sub->whereHas('movements.employee', function ($e) use ($values) {
                            $e->whereIn('department_id', $values);
                        });
                    }
                    if ($hasNull) {
                        $sub->orWhereDoesntHave('movements.employee')
                            ->orWhereHas('movements.employee', function ($e) {
                                $e->whereNull('department_id');
                            });
                    }
                });
            })
            ->when(! empty($this->filterOrganization), function ($q) {
                $hasNull = in_array('null', $this->filterOrganization, true) || in_array(null, $this->filterOrganization, true);
                $values = array_filter($this->filterOrganization, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($values, $hasNull) {
                    if (! empty($values)) {
                        $sub->whereHas('assets.holder', function ($h) use ($values) {
                            $h->whereIn('organization_id', $values);
                        });
                    }
                    if ($hasNull) {
                        $sub->orWhereDoesntHave('assets.holder')
                            ->orWhereHas('assets.holder', function ($h) {
                                $h->whereNull('organization_id');
                            });
                    }
                });
            })
            ->when(! empty($this->filterBrand), function ($q) {
                $hasNull = in_array('null', $this->filterBrand, true) || in_array(null, $this->filterBrand, true);
                $values = array_filter($this->filterBrand, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($values, $hasNull) {
                    if (! empty($values)) {
                        $sub->whereHas('assets.model', function ($c) use ($values) {
                            $c->whereIn('brand_id', $values);
                        });
                    }
                    if ($hasNull) {
                        $sub->orWhereDoesntHave('assets.model')
                            ->orWhereHas('assets.model', function ($c) {
                                $c->whereNull('brand_id');
                            });
                    }
                });
            })
            ->orderBy($actualSort, $this->sortDirection)
            ->paginate(15);

        return view('livewire.admin.equipment-manager', [
            'equipments' => $equipments,
            'types' => EquipmentType::select('id', 'model_name')->get(),
            'categoriesList' => EquipmentCategory::select('id', 'category_name')->get(),
            'locationsList' => Location::select('id', 'room_number')->get(),
            'employeesList' => Employee::select('id', 'first_name', 'last_name')->get(),
            'departmentsList' => Department::select('id', 'name')->get(),
            'organizationsList' => Organization::select('id', 'org_name')->get(),
            'brandsList' => BrandTz::select('id', 'brandtz_name')->get(),
        ]);
    }
}
