<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Equipment;

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
            $q->where('inv_number', 'like', '%' . $search . '%')
              ->orWhere('account_name', 'like', '%' . $search . '%');
        })
        ->when(!empty($filterType), function ($q) use ($filterType) {
            $q->whereHas('assets', function ($c) use ($filterType) {
                $c->whereIn('model_id', $filterType);
            });
        })
        ->when(!empty($filterStatus), function ($q) use ($filterStatus) {
            $q->whereIn('status', $filterStatus);
        })
        ->when(!empty($filterCategory), function ($q) use ($filterCategory) {
            $q->whereHas('assets.componentType', function ($c) use ($filterCategory) {
                $c->whereIn('category_id', $filterCategory);
            });
        })
        ->when(!empty($filterLocation), function ($q) use ($filterLocation) {
            $q->whereHas('assets', function ($c) use ($filterLocation) {
                $c->whereIn('current_loc_id', $filterLocation);
            });
        })
        ->when(!empty($filterEmployee), function ($q) use ($filterEmployee) {
            $q->whereHas('movements', function ($m) use ($filterEmployee) {
                $m->whereIn('employee_id', $filterEmployee);
            });
        })
        ->when(!empty($filterDepartment), function ($q) use ($filterDepartment) {
            $q->whereHas('movements.employee', function ($e) use ($filterDepartment) {
                $e->whereIn('department_id', $filterDepartment);
            });
        })
        ->when(!empty($filterOrganization), function ($q) use ($filterOrganization) {
            $q->whereHas('assets.holder', function ($h) use ($filterOrganization) {
                $h->whereIn('organization_id', $filterOrganization);
            });
        })
        ->when(!empty($filterBrand), function ($q) use ($filterBrand) {
            $q->whereHas('assets.model', function ($c) use ($filterBrand) {
                $c->whereIn('brand_id', $filterBrand);
            });
        })
        ->orderBy('id', 'desc')
        ->get();

        return view('admin.equipment-report', [
            'equipments' => $equipments,
            'filters'    => $request->all(),
            'generatedAt' => now()->format('d.m.Y H:i'),
        ]);
    }
}
