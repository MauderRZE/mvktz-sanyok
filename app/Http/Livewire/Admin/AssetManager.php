<?php

namespace App\Http\Livewire\Admin;

use App\Livewire\Forms\AssetForm;
use App\Models\Asset;
use App\Models\BaseComponent;
use App\Models\BrandTz;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Models\Location;
use App\Models\LocationHolder;
use App\Models\LowValueMaterial;
use App\Models\LowValueWriteOffAct;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AssetManager extends Component
{
    use WithPagination;

    public AssetForm $form;

    public $isOpen = false;

    public $isViewOpen = false;

    public $viewAsset = null;

    public array $expandedRows = [];

    // Пошук та сортування
    public $search = '';

    public $sortField = 'id';

    public $sortDirection = 'desc';

    // Фільтри
    public $filterStatus = [];

    public $filterBaseComponent = [];

    public $filterLocation = [];

    public $filterHolder = [];

    public $filterModel = [];

    public $filterNetwork = [];

    public $filterCategory = [];

    public $filterBrand = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterBaseComponent()
    {
        $this->resetPage();
    }

    public function updatingFilterLocation()
    {
        $this->resetPage();
    }

    public function updatingFilterHolder()
    {
        $this->resetPage();
    }

    public function updatingFilterModel()
    {
        $this->resetPage();
    }

    public function updatingFilterNetwork()
    {
        $this->resetPage();
    }

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    public function updatingFilterBrand()
    {
        $this->resetPage();
    }

    public function updatedFilterCategory()
    {
        $this->resetPage();
        if (! empty($this->filterCategory) && ! empty($this->filterBaseComponent)) {
            $validComponentIds = BaseComponent::whereIn('category_id', $this->filterCategory)
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->toArray();
            $this->filterBaseComponent = array_values(array_filter($this->filterBaseComponent, function ($id) use ($validComponentIds) {
                return in_array((string) $id, $validComponentIds, true);
            }));
        }
    }

    public function updatedFilterBrand()
    {
        $this->resetPage();
        if (! empty($this->filterModel) && ! empty($this->filterBrand)) {
            $validModelIds = EquipmentType::whereIn('id', $this->filterModel)
                ->whereIn('brand_id', $this->filterBrand)
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->toArray();
            $this->filterModel = array_values(array_filter($this->filterModel, function ($id) use ($validModelIds) {
                return in_array((string) $id, $validModelIds, true);
            }));
        }
    }

    public function updatedFormBaseComponentId($value)
    {
        $this->form->handleBaseComponentChange($value);
        $this->form->model_id = null;

        if (empty($value)) {
            $this->resetValidation('form.base_component_id'); // <-- Скидає червоний напис помилки
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatus = [];
        $this->filterBaseComponent = [];
        $this->filterLocation = [];
        $this->filterHolder = [];
        $this->filterModel = [];
        $this->filterNetwork = [];
        $this->filterCategory = [];
        $this->filterBrand = [];
        $this->resetPage();
    }

    public function sortBy($field)
    {
        $this->sortDirection = ($this->sortField === $field)
            ? ($this->sortDirection === 'asc' ? 'desc' : 'asc')
            : 'asc';
        $this->sortField = $field;
        $this->resetPage();
    }

    public function toggleRow(int $id): void
    {
        if (in_array($id, $this->expandedRows)) {
            $this->expandedRows = array_values(
                array_filter($this->expandedRows, fn ($r) => $r !== $id)
            );
        } else {
            $this->expandedRows[] = $id;
        }
    }

    #[On('assetSaved')]
    public function refreshAssets()
    {
        // just to trigger a re-render
    }

    public function updatedFormModelId($value)
    {
        if (! $value) {
            return;
        }

        $model = EquipmentType::find($value);

        if ($model && $model->base_component_id) {
            // 1. Присвоюємо ID компонента прямо у форму як integer
            $this->form->base_component_id = (int) $model->base_component_id;

            // 2. Викликаємо внутрішній обробник форми, передаючи йому ID компонента
            $this->form->handleBaseComponentChange((int) $model->base_component_id);
        }
    }

    public function render()
    {
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
        ])->withCount('childAssets');

        if (! empty($this->expandedRows)) {
            $query->with([
                'childAssets.componentType',
                'childAssets.model.brand',
                'childAssets.location',
                'childAssets.holder.employee',
                'childAssets.holder.organization',
                'childAssets.equipment',
                'childAssets.itemProperties.attribute',
                'itemProperties.attribute',
            ]);
        }

        $query->when($this->search, function ($q) {
            $q->where(function ($q) {
                $search = '%'.$this->search.'%';
                $q->where('assets.serial_number', 'like', $search)
                    ->orWhere('assets.notes', 'like', $search)
                    ->orWhere('assets.ip_address', 'like', $search)
                    ->orWhere('assets.mac_address', 'like', $search)
                    ->orWhere('assets.hostname', 'like', $search)
                    ->orWhereHas('equipment', function ($eq) use ($search) {
                        $eq->where('inv_number', 'like', $search)
                            ->orWhere('account_name', 'like', $search);
                    })
                    ->orWhereHas('componentType', function ($ct) use ($search) {
                        $ct->where('component_name', 'like', $search);
                    })
                    ->orWhereHas('model', function ($m) use ($search) {
                        $m->where('model_name', 'like', $search)
                            ->orWhereHas('brand', function ($b) use ($search) {
                                $b->where('brandtz_name', 'like', $search);
                            });
                    })
                    ->orWhereHas('location', function ($loc) use ($search) {
                        $loc->where('room_number', 'like', $search);
                    })
                    ->orWhereHas('holder', function ($h) use ($search) {
                        $h->whereHas('employee', function ($emp) use ($search) {
                            $emp->where('last_name', 'like', $search)
                                ->orWhere('first_name', 'like', $search);
                        })->orWhereHas('organization', function ($org) use ($search) {
                            $org->where('org_name', 'like', $search);
                        });
                    })
                    ->orWhereHas('lowValueMaterial', function ($lvm) use ($search) {
                        $lvm->where('nomenklature_number', 'like', $search)
                            ->orWhere('material_account_name', 'like', $search);
                    })
                    ->orWhereHas('writeOffAct', function ($woa) use ($search) {
                        $woa->where('act_number', 'like', $search);
                    });
            });
        })
            ->when(! empty($this->filterStatus), function ($q) {
                $hasNull = in_array('null', $this->filterStatus, true) || in_array(null, $this->filterStatus, true);
                $values = array_filter($this->filterStatus, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($values, $hasNull) {
                    if (! empty($values)) {
                        $sub->whereIn('assets.status', $values);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('assets.status');
                    }
                });
            })
            ->when(! empty($this->filterBaseComponent), function ($q) {
                $hasNull = in_array('null', $this->filterBaseComponent, true) || in_array(null, $this->filterBaseComponent, true);
                $values = array_filter($this->filterBaseComponent, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($values, $hasNull) {
                    if (! empty($values)) {
                        $sub->whereIn('assets.base_component_id', $values);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('assets.base_component_id');
                    }
                });
            })
            ->when(! empty($this->filterLocation), function ($q) {
                $hasNull = in_array('null', $this->filterLocation, true) || in_array(null, $this->filterLocation, true);
                $values = array_filter($this->filterLocation, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($values, $hasNull) {
                    if (! empty($values)) {
                        $sub->whereIn('assets.current_loc_id', $values);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('assets.current_loc_id');
                    }
                });
            })
            ->when(! empty($this->filterHolder), function ($q) {
                $hasNull = in_array('null', $this->filterHolder, true) || in_array(null, $this->filterHolder, true);
                $values = array_filter($this->filterHolder, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($values, $hasNull) {
                    if (! empty($values)) {
                        $sub->whereIn('assets.current_holder_id', $values);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('assets.current_holder_id');
                    }
                });
            })
            ->when(! empty($this->filterModel), function ($q) {
                $hasNull = in_array('null', $this->filterModel, true) || in_array(null, $this->filterModel, true);
                $values = array_filter($this->filterModel, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($values, $hasNull) {
                    if (! empty($values)) {
                        $sub->whereIn('assets.model_id', $values);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('assets.model_id');
                    }
                });
            })
            ->when(! empty($this->filterNetwork), function ($q) {
                $wantsYes = in_array(1, $this->filterNetwork) || in_array('1', $this->filterNetwork, true);
                $wantsNo = in_array(0, $this->filterNetwork) || in_array('0', $this->filterNetwork, true);

                if ($wantsYes && ! $wantsNo) {
                    $q->where(function ($sub) {
                        $sub->whereNotNull('assets.ip_address')->where('assets.ip_address', '!=', '')
                            ->orWhereNotNull('assets.mac_address')->where('assets.mac_address', '!=', '')
                            ->orWhereNotNull('assets.hostname')->where('assets.hostname', '!=', '');
                    });
                } elseif ($wantsNo && ! $wantsYes) {
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
            ->when(! empty($this->filterCategory), function ($q) {
                $hasNull = in_array('null', $this->filterCategory, true) || in_array(null, $this->filterCategory, true);
                $values = array_filter($this->filterCategory, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($values, $hasNull) {
                    if (! empty($values)) {
                        $sub->whereHas('componentType', function ($subQ) use ($values) {
                            $subQ->whereIn('category_id', $values);
                        });
                    }
                    if ($hasNull) {
                        $sub->orWhereDoesntHave('componentType')
                            ->orWhereHas('componentType', function ($subQ) {
                                $subQ->whereNull('category_id');
                            });
                    }
                });
            })
            ->when(! empty($this->filterBrand), function ($q) {
                $hasNull = in_array('null', $this->filterBrand, true) || in_array(null, $this->filterBrand, true);
                $values = array_filter($this->filterBrand, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($values, $hasNull) {
                    if (! empty($values)) {
                        $sub->whereHas('model', function ($subQ) use ($values) {
                            $subQ->whereIn('brand_id', $values);
                        });
                    }
                    if ($hasNull) {
                        $sub->orWhereDoesntHave('model')
                            ->orWhereHas('model', function ($subQ) {
                                $subQ->whereNull('brand_id');
                            });
                    }
                });
            })
            ->when(empty($this->search) && empty($this->filterStatus) && empty($this->filterBaseComponent) && empty($this->filterLocation) && empty($this->filterHolder) && empty($this->filterModel) && empty($this->filterNetwork) && empty($this->filterCategory) && empty($this->filterBrand), function ($q) {
                $q->whereNull('assets.parent_asset_id');
            });

        if ($this->sortField === 'equipment') {
            $query->leftJoin('equipment', 'assets.equipment_id', '=', 'equipment.id')
                ->select('assets.*')
                ->orderBy('equipment.inv_number', $this->sortDirection);
        } elseif ($this->sortField === 'component_type') {
            $query->leftJoin('base_components', 'assets.base_component_id', '=', 'base_components.id')
                ->select('assets.*')
                ->orderBy('base_components.component_name', $this->sortDirection);
        } elseif ($this->sortField === 'location') {
            $query->leftJoin('locations', 'assets.current_loc_id', '=', 'locations.id')
                ->select('assets.*')
                ->orderBy('locations.room_number', $this->sortDirection);
        } elseif (in_array($this->sortField, ['id', 'serial_number', 'status', 'ip_address', 'mac_address'])) {
            $query->orderBy('assets.'.$this->sortField, $this->sortDirection);
        } else {
            $query->orderBy('assets.id', 'desc');
        }

        $baseComponentsQuery = BaseComponent::select('id', 'component_name', 'category_id')->orderBy('component_name');
        if (! empty($this->filterCategory)) {
            $baseComponentsQuery->whereIn('category_id', $this->filterCategory);
        }
        if (! empty($this->filterModel)) {
            $baseComponentsQuery->whereHas('assets', function ($q) {
                $q->whereIn('model_id', $this->filterModel);
            });
        } elseif (! empty($this->filterBrand)) {
            $baseComponentsQuery->whereHas('assets.model', function ($q) {
                $q->whereIn('brand_id', $this->filterBrand);
            });
        }

        $modelsQuery = EquipmentType::with('brand:id,brandtz_name')
            ->select('id', 'model_name', 'brand_id')
            ->orderBy('model_name');
        if (! empty($this->filterBrand)) {
            $modelsQuery->whereIn('brand_id', $this->filterBrand);
        }

        if (! empty($this->filterBaseComponent)) {
            $modelsQuery->whereHas('assets', function ($q) {
                $q->whereIn('base_component_id', $this->filterBaseComponent);
            });
        }

        $data = [
            'assets' => $query->paginate(15),
            'categoriesList' => EquipmentCategory::select('id', 'category_name')->orderBy('category_name')->get(),
            'baseComponentsList' => $baseComponentsQuery->get(),
            'brandsList' => BrandTz::select('id', 'brandtz_name')->orderBy('brandtz_name')->get(),
            'modelsList' => $modelsQuery->get(),
            'locationsList' => Location::select('id', 'room_number')->get(),
            'holdersList' => LocationHolder::with(['employee:id,first_name,last_name', 'organization:id,org_name'])->select('id', 'employee_id', 'organization_id')->get(),
        ];

        if ($this->isOpen) {
            $data['equipmentList'] = Equipment::select('id', 'inv_number', 'account_name')->get();
            $data['modelsList'] = EquipmentType::with('brand:id,brandtz_name')
                ->select('id', 'model_name', 'brand_id')
                ->when($this->form->base_component_id, function ($q) {
                    // Фільтруємо моделі за базовим компонентом напряму:
                    $q->where('base_component_id', $this->form->base_component_id);
                })
                ->orderBy('model_name')
                ->get();
            $data['parentAssetsList'] = Asset::with(['componentType:id,component_name', 'equipment:id,inv_number'])
                ->select('id', 'base_component_id', 'equipment_id', 'serial_number')
                ->whereHas('componentType', function ($q) {
                    $q->whereIn('component_name', ['Системний блок', 'Ноутбук']);
                })->get();
            $data['nomenclaturesList'] = LowValueMaterial::select('id', 'material_account_name', 'nomenklature_number')->get();
            $data['writeOffActsList'] = LowValueWriteOffAct::select('id', 'act_number')->get();
        } else {
            $data['equipmentList'] = collect();
            $data['parentAssetsList'] = collect();
            $data['nomenclaturesList'] = collect();
            $data['writeOffActsList'] = collect();
        }

        return view('livewire.admin.asset-manager', $data);
    }

    public function create()
    {
        $this->form->reset();
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

    public function view($id)
    {
        $this->viewAsset = Asset::with([
            'equipment',
            'componentType',
            'model.brand',
            'location',
            'holder.employee',
            'holder.organization',
            'parentAsset.componentType',
            'lowValueMaterial',
            'writeOffAct',
            'itemProperties.attribute',
        ])->find($id);
        $this->isViewOpen = true;
    }

    public function closeView()
    {
        $this->isViewOpen = false;
        $this->viewAsset = null;
    }

    public function close()
    {
        if ($this->isViewOpen) {
            $this->isViewOpen = false;
            $this->viewAsset = null;
        }
    }

    public function store()
    {
        $isUpdate = $this->form->store();

        session()->flash('message', $isUpdate ? 'Актив оновлено.' : 'Актив додано.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $comp = Asset::findOrFail($id);
        $this->form->setAsset($comp);
        $this->openModal();
    }

    public function delete($id)
    {
        Asset::find($id)->delete();
        session()->flash('message', 'Актив видалено.');
    }
}
