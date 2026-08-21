<?php

namespace App\Http\Controllers\Admin;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AssetReportController extends Controller
{
    public function index(Request $request)
    {
        
        $search = $request->get('search', '');
        $filterStatus = $request->get('filterStatus', []);
        $filterBaseComponent = $request->get('filterBaseComponent', []);
        $filterLocation = $request->get('filterLocation', []);
        $filterHolder = $request->get('filterHolder', []);
        $filterModel = $request->get('filterModel', []);
        $filterNetwork = $request->get('filterNetwork', []);
        $filterCategory = $request->get('filterCategory', []);
        $filterYears = $request->get('filterYears', []);
        $filterAssetTypes = $request->get('filterAssetTypes', []);

        $applyNullableFilter = function ($query, $column, $selectedValues) {
            if (empty($selectedValues)) {
                return;
            }

            $values = (array) $selectedValues;

            $hasNull = in_array('null', $values, true) || in_array('NULL', $values, true) || in_array(null, $values, true);
            $cleanValues = array_values(array_filter($values, function ($v) {
                return $v !== 'null' && $v !== 'NULL' && !is_null($v) && $v !== '';
            }));

            $query->where(function ($subQ) use ($column, $hasNull, $cleanValues) {
                if (!empty($cleanValues)) {
                    $subQ->whereIn($column, $cleanValues);
                    if ($hasNull) {
                        $subQ->orWhereNull($column);
                    }
                } elseif ($hasNull) {
                    $subQ->whereNull($column);
                }
            });
        };

        $query = Asset::with([
            'equipment',
            'componentType',
            'model.brand',
            'location',
            'holder.employee',
            'holder.organization',
            'parentAsset.componentType',
            'lowValueMaterial',
            'writeOffAct',
            'childAssets.componentType',
            'childAssets.model.brand',
            'childAssets.location',
            'childAssets.holder.employee',
            'childAssets.holder.organization',
            'childAssets.equipment',
        ])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $searchLike = '%' . $search . '%';
                    $q->where('assets.serial_number', 'like', $searchLike)
                        ->orWhere('assets.notes', 'like', $searchLike)
                        ->orWhere('assets.ip_address', 'like', $searchLike)
                        ->orWhere('assets.mac_address', 'like', $searchLike)
                        ->orWhere('assets.hostname', 'like', $searchLike)
                        ->orWhereHas('equipment', function ($eq) use ($searchLike) {
                            $eq->where('inv_number', 'like', $searchLike)
                                ->orWhere('account_name', 'like', $searchLike);
                        })
                        ->orWhereHas('componentType', function ($ct) use ($searchLike) {
                            $ct->where('component_name', 'like', $searchLike);
                        })
                        ->orWhereHas('model', function ($m) use ($searchLike) {
                            $m->where('model_name', 'like', $searchLike)
                                ->orWhereHas('brand', function ($b) use ($searchLike) {
                                    $b->where('brandtz_name', 'like', $searchLike);
                                });
                        })
                        ->orWhereHas('location', function ($loc) use ($searchLike) {
                            $loc->where('room_number', 'like', $searchLike);
                        })
                        ->orWhereHas('holder', function ($h) use ($searchLike) {
                            $h->whereHas('employee', function ($emp) use ($searchLike) {
                                $emp->where('last_name', 'like', $searchLike)
                                    ->orWhere('first_name', 'like', $searchLike);
                            })->orWhereHas('organization', function ($org) use ($searchLike) {
                                $org->where('org_name', 'like', $searchLike);
                            });
                        })
                        ->orWhereHas('lowValueMaterial', function ($lvm) use ($searchLike) {
                            $lvm->where('nomenklature_number', 'like', $searchLike)
                                ->orWhere('material_account_name', 'like', $searchLike);
                        })
                        ->orWhereHas('writeOffAct', function ($woa) use ($searchLike) {
                            $woa->where('act_number', 'like', $searchLike);
                        });
                });
            })
            ->when(!empty($filterStatus), fn($q) => $applyNullableFilter($q, 'assets.status', $filterStatus))
            ->when(!empty($filterBaseComponent), fn($q) => $applyNullableFilter($q, 'assets.base_component_id', $filterBaseComponent))
            ->when(!empty($filterLocation), fn($q) => $applyNullableFilter($q, 'assets.current_loc_id', $filterLocation))
            ->when(!empty($filterHolder), fn($q) => $applyNullableFilter($q, 'assets.current_holder_id', $filterHolder))
            ->when(!empty($filterModel), fn($q) => $applyNullableFilter($q, 'assets.model_id', $filterModel))
            ->when(!empty($filterNetwork), function ($q) use ($filterNetwork) {
                $wantsYes = in_array(1, $filterNetwork) || in_array('1', $filterNetwork, true);
                $wantsNo = in_array(0, $filterNetwork) || in_array('0', $filterNetwork, true);

                if ($wantsYes && !$wantsNo) {
                    $q->where(function ($sub) {
                        $sub->whereNotNull('assets.ip_address')->where('assets.ip_address', '!=', '')
                            ->orWhereNotNull('assets.mac_address')->where('assets.mac_address', '!=', '')
                            ->orWhereNotNull('assets.hostname')->where('assets.hostname', '!=', '');
                    });
                } elseif ($wantsNo && !$wantsYes) {
                    $q->where(function ($sub) {
                        $sub->where(function ($s) {
                            $s->whereNull('assets.ip_address')->orWhere('assets.ip_address', '');
                        })->where(function ($s) {
                            $s->whereNull('assets.mac_address')->orWhere('assets.mac_address', '');
                        })->where(function ($s) {
                            $s->whereNull('assets.hostname')->orWhere('assets.hostname', '');
                        });
                    });
                }
            })
            ->when(!empty($filterCategory), function ($q) use ($filterCategory) {
                $values = (array) $filterCategory;
                $hasNull = in_array('null', $values, true) || in_array('NULL', $values, true) || in_array(null, $values, true);
                $cleanValues = array_values(array_filter($values, fn($v) => $v !== 'null' && $v !== 'NULL' && !is_null($v) && $v !== ''));

                $q->where(function ($subQ) use ($hasNull, $cleanValues) {
                    if (!empty($cleanValues)) {
                        $subQ->whereHas('componentType', fn($ct) => $ct->whereIn('category_id', $cleanValues));
                        if ($hasNull) {
                            $subQ->orWhereDoesntHave('componentType')
                                ->orWhereHas('componentType', fn($ct) => $ct->whereNull('category_id'));
                        }
                    } elseif ($hasNull) {
                        $subQ->whereDoesntHave('componentType')
                            ->orWhereHas('componentType', fn($ct) => $ct->whereNull('category_id'));
                    }
                });
            })
            ->when(!empty($filterYears), function ($q) use ($filterYears) {
                $values = (array) $filterYears;
                $hasNull = !empty(array_intersect(['null', 'NULL', null], $values));
                $cleanValues = array_values(array_filter($values, fn($v) => !in_array($v, ['null', 'NULL', null, ''], true)));

                $q->where(function ($subY) use ($hasNull, $cleanValues) {
                    if (!empty($cleanValues)) {
                        $subY->whereIn('assets.purchase_year', $cleanValues);
                    }
                    if ($hasNull) {
                        if (!empty($cleanValues)) {
                            $subY->orWhereNull('assets.purchase_year');
                        } else {
                            $subY->whereNull('assets.purchase_year');
                        }
                    }
                });
            })
            ->when(!empty($filterAssetTypes), function ($q) use ($filterAssetTypes) {
                $types = (array) $filterAssetTypes;

                // Якщо обрано обидва — не обмежуємо вибірку
                if (in_array('main', $types) && in_array('msh', $types)) {
                    return;
                }

                if (in_array('main', $types)) {
                    // ОЗ: є інвентарник і немає номенклатури малоцінки
                    $q->whereNotNull('assets.equipment_id')
                        ->whereNull('assets.nomenclature_id');
                } elseif (in_array('msh', $types)) {
                    // МШП: є валідна номенклатура
                    $q->whereNotNull('assets.nomenclature_id')
                        ->where('assets.nomenclature_id', '!=', 0);
                }
            })
            ->when(empty($search) && empty($filterStatus) && empty($filterBaseComponent) && empty($filterLocation) && empty($filterHolder) && empty($filterModel) && empty($filterNetwork) && empty($filterCategory) && empty($filterYears) && empty($filterAssetTypes), function ($q) {
                $q->whereNull('assets.parent_asset_id');
            });

        $assets = $query->leftJoin('equipment', 'assets.equipment_id', '=', 'equipment.id')
            ->select('assets.*')
            ->orderByRaw('CASE WHEN equipment.inv_number IS NULL OR equipment.inv_number = "" THEN 1 ELSE 0 END')
            ->orderBy('equipment.inv_number', 'asc')
            ->orderBy('assets.id', 'asc')
            ->get();

        return view('admin.asset-report', [
            'assets' => $assets,
            'filters' => $request->all(),
            'generatedAt' => now()->format('d.m.Y H:i'),
        ]);
    }
}
