<?php

namespace App\Http\Controllers\Admin;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EquipmentReportController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $filterType = $request->get('filterType', []);
        $filterStatus = $request->get('filterStatus', []);
        $filterCategory = $request->get('filterCategory', []);
        $filterLocation = $request->get('filterLocation', []);
        $filterEmployee = $request->get('filterEmployee', []);
        $filterDepartment = $request->get('filterDepartment', []);
        $filterOrganization = $request->get('filterOrganization', []);
        $filterBrand = $request->get('filterBrand', []);

        $equipments = Equipment::with([
            'assets.componentType',
            'movements.asset.location',
            'movements.employee',
        ])
            ->when($search, function ($q) use ($search) {
                $q->where('inv_number', 'like', '%'.$search.'%')
                    ->orWhere('account_name', 'like', '%'.$search.'%');
            })
            ->when(! empty($filterType), function ($q) use ($filterType) {
                $hasNull = in_array('null', (array) $filterType, true) || in_array(null, (array) $filterType, true);
                $values = array_filter((array) $filterType, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
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
            ->when(! empty($filterStatus), function ($q) use ($filterStatus) {
                $hasNull = in_array('null', (array) $filterStatus, true) || in_array(null, (array) $filterStatus, true);
                $values = array_filter((array) $filterStatus, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($values, $hasNull) {
                    if (! empty($values)) {
                        $sub->whereIn('status', $values);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('status');
                    }
                });
            })
            ->when(! empty($filterCategory), function ($q) use ($filterCategory) {
                $hasNull = in_array('null', (array) $filterCategory, true) || in_array(null, (array) $filterCategory, true);
                $values = array_filter((array) $filterCategory, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
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
            ->when(! empty($filterLocation), function ($q) use ($filterLocation) {
                $hasNull = in_array('null', (array) $filterLocation, true) || in_array(null, (array) $filterLocation, true);
                $values = array_filter((array) $filterLocation, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
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
            ->when(! empty($filterEmployee), function ($q) use ($filterEmployee) {
                $hasNull = in_array('null', (array) $filterEmployee, true) || in_array(null, (array) $filterEmployee, true);
                $values = array_filter((array) $filterEmployee, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
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
            ->when(! empty($filterDepartment), function ($q) use ($filterDepartment) {
                $hasNull = in_array('null', (array) $filterDepartment, true) || in_array(null, (array) $filterDepartment, true);
                $values = array_filter((array) $filterDepartment, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
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
            ->when(! empty($filterOrganization), function ($q) use ($filterOrganization) {
                $hasNull = in_array('null', (array) $filterOrganization, true) || in_array(null, (array) $filterOrganization, true);
                $values = array_filter((array) $filterOrganization, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
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
            ->when(! empty($filterBrand), function ($q) use ($filterBrand) {
                $hasNull = in_array('null', (array) $filterBrand, true) || in_array(null, (array) $filterBrand, true);
                $values = array_filter((array) $filterBrand, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
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
            ->orderBy('inv_number', 'asc')
            ->get();

        return view('admin.equipment-report', [
            'equipments' => $equipments,
            'filters' => $request->all(),
            'generatedAt' => now()->format('d.m.Y H:i'),
        ]);
    }
}
