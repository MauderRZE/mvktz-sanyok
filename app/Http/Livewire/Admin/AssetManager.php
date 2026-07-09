<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Asset;
use App\Models\Equipment;
use App\Models\BaseComponent;
use App\Models\EquipmentType;
use App\Models\Location;
use App\Models\LocationHolder;
use App\Models\LowValueMaterial;
use App\Models\LowValueWriteOffAct;
use App\Models\EquipmentCategory;
use App\Livewire\Forms\AssetForm;

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

    public function updatingFilterLocation() { $this->resetPage(); }
    public function updatingFilterHolder() { $this->resetPage(); }
    public function updatingFilterModel() { $this->resetPage(); }
    public function updatingFilterNetwork() { $this->resetPage(); }
    public function updatingFilterCategory() { $this->resetPage(); }

    public function updatedFormBaseComponentId($value)
    {
        $this->form->handleBaseComponentChange($value);
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
        $this->resetPage();
    }

    public function sortBy($field)
    {
        $this->sortDirection = ($this->sortField === $field)
            ? ($this->sortDirection === 'asc' ? 'desc' : 'asc')
            : 'asc';
        $this->sortField = $field;
    }

    public function toggleRow(int $id): void
    {
        if (in_array($id, $this->expandedRows)) {
            $this->expandedRows = array_values(
                array_filter($this->expandedRows, fn($r) => $r !== $id)
            );
        } else {
            $this->expandedRows[] = $id;
        }
    }

    #[\Livewire\Attributes\On('assetSaved')]
    public function refreshAssets()
    {
        // just to trigger a re-render
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

        if (!empty($this->expandedRows)) {
            $query->with([
                'childAssets.componentType',
                'childAssets.model.brand',
                'childAssets.location',
                'childAssets.holder.employee',
                'childAssets.holder.organization',
                'childAssets.equipment',
            ]);
        }
        
        $query->when($this->search, function($q) {
                $q->where(function($q) {
                    $search = '%' . $this->search . '%';
                    $q->where('serial_number', 'like', $search)
                      ->orWhere('notes', 'like', $search)
                      ->orWhere('ip_address', 'like', $search)
                      ->orWhere('mac_address', 'like', $search)
                      ->orWhere('hostname', 'like', $search)
                      ->orWhereHas('equipment', function($eq) use ($search) {
                          $eq->where('inv_number', 'like', $search)
                             ->orWhere('account_name', 'like', $search);
                      })
                      ->orWhereHas('componentType', function($ct) use ($search) {
                          $ct->where('component_name', 'like', $search);
                      })
                      ->orWhereHas('model', function($m) use ($search) {
                          $m->where('model_name', 'like', $search)
                            ->orWhereHas('brand', function($b) use ($search) {
                                $b->where('brandtz_name', 'like', $search);
                            });
                      })
                      ->orWhereHas('location', function($loc) use ($search) {
                          $loc->where('room_number', 'like', $search);
                      })
                      ->orWhereHas('holder', function($h) use ($search) {
                          $h->whereHas('employee', function($emp) use ($search) {
                              $emp->where('last_name', 'like', $search)
                                  ->orWhere('first_name', 'like', $search);
                          })->orWhereHas('organization', function($org) use ($search) {
                              $org->where('org_name', 'like', $search);
                          });
                      })
                      ->orWhereHas('lowValueMaterial', function($lvm) use ($search) {
                          $lvm->where('nomenklature_number', 'like', $search)
                              ->orWhere('material_account_name', 'like', $search);
                      })
                      ->orWhereHas('writeOffAct', function($woa) use ($search) {
                          $woa->where('act_number', 'like', $search);
                      });
                });
            })
            ->when(!empty($this->filterStatus), function($q) {
                $q->whereIn('status', $this->filterStatus);
            })
            ->when(!empty($this->filterBaseComponent), function($q) {
                $q->whereIn('base_component_id', $this->filterBaseComponent);
            })
            ->when(!empty($this->filterLocation), function($q) {
                $q->whereIn('current_loc_id', $this->filterLocation);
            })
            ->when(!empty($this->filterHolder), function($q) {
                $q->whereIn('current_holder_id', $this->filterHolder);
            })
            ->when(!empty($this->filterModel), function($q) {
                $q->whereIn('model_id', $this->filterModel);
            })
            ->when(!empty($this->filterNetwork), function($q) {
                $wantsYes = in_array(1, $this->filterNetwork) || in_array('1', $this->filterNetwork, true);
                $wantsNo = in_array(0, $this->filterNetwork) || in_array('0', $this->filterNetwork, true);
                
                if ($wantsYes && !$wantsNo) {
                    $q->where(function ($sub) {
                        $sub->whereNotNull('ip_address')->where('ip_address', '!=', '')
                            ->orWhereNotNull('mac_address')->where('mac_address', '!=', '')
                            ->orWhereNotNull('hostname')->where('hostname', '!=', '');
                    });
                } elseif ($wantsNo && !$wantsYes) {
                    $q->where(function ($sub) {
                        $sub->where(function($s) {
                            $s->whereNull('ip_address')->orWhere('ip_address', '');
                        })->where(function($s) {
                            $s->whereNull('mac_address')->orWhere('mac_address', '');
                        })->where(function($s) {
                            $s->whereNull('hostname')->orWhere('hostname', '');
                        });
                    });
                }
            })
            ->when(!empty($this->filterCategory), function($q) {
                $q->whereHas('componentType', function($subQ) {
                    $subQ->whereIn('category_id', $this->filterCategory);
                });
            })
            ->when(empty($this->search) && empty($this->filterStatus) && empty($this->filterBaseComponent) && empty($this->filterLocation) && empty($this->filterHolder) && empty($this->filterModel) && empty($this->filterNetwork) && empty($this->filterCategory), function($q) {
                $q->whereNull('parent_asset_id');
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
            $query->orderBy('assets.' . $this->sortField, $this->sortDirection);
        } else {
            $query->orderBy('assets.id', 'desc');
        }

        $data = [
            'assets' => $query->paginate(15),
            'categoriesList' => EquipmentCategory::select('id', 'category_name')->orderBy('category_name')->get(),
            'baseComponentsList' => BaseComponent::select('id', 'component_name')->get(),
            'modelsList' => EquipmentType::with('brand:id,brandtz_name')->select('id', 'model_name', 'brand_id')->get(),
            'locationsList' => Location::select('id', 'room_number')->get(),
            'holdersList' => LocationHolder::with(['employee:id,first_name,last_name', 'organization:id,org_name'])->select('id', 'employee_id', 'organization_id')->get(),
        ];

        if ($this->isOpen) {
            $data['equipmentList'] = Equipment::select('id', 'inv_number', 'account_name')->get();
            $data['parentAssetsList'] = Asset::with(['componentType:id,component_name', 'equipment:id,inv_number'])
                ->select('id', 'base_component_id', 'equipment_id', 'serial_number')
                ->whereHas('componentType', function($q) {
                    $q->where('component_name', 'Системний блок');
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
            'writeOffAct'
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

        session()->flash('message', 
            $isUpdate ? 'Актив оновлено.' : 'Актив додано.');

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
