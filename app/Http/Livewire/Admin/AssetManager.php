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

#[Layout('layouts.admin')]
class AssetManager extends Component
{
    use WithPagination;

    public $assetId, $equipment_id, $base_component_id, $model_id;
    public $current_loc_id, $current_holder_id, $parent_asset_id;
    public $notes, $serial_number;
    public $has_network = 0, $ip_address, $mac_address, $hostname;
    public $status = 'Працює';
    public $nomenclature_id, $write_off_act_id;

    public $isOpen = 0;

    public array $expandedRows = [];

    public $isFilterOpen = false;
    public $isViewOpen = false;
    public $viewAsset = null;

    // Пошук та сортування
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';

    // Фільтри
    public $filterStatus = [];
    public $filterBaseComponent = [];

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

    public function updatedBaseComponentId($value)
    {
        if ($value) {
            $component = BaseComponent::find($value);
            if ($component && mb_strtolower($component->component_name) === 'системний блок') {
                $this->parent_asset_id = null;
            }
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatus = [];
        $this->filterBaseComponent = [];
        $this->resetPage();
    }

    public function openFilters()
    {
        $this->isFilterOpen = true;
    }

    public function closeFilters()
    {
        $this->isFilterOpen = false;
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
                'childAssets.componentType',
                'childAssets.model.brand',
                'childAssets.location',
                'childAssets.holder.employee',
                'childAssets.holder.organization',
                'childAssets.equipment',
            ])
            ->when($this->search, function($q) {
                $q->where(function($q) {
                    $q->where('serial_number', 'like', '%' . $this->search . '%')
                      ->orWhere('notes', 'like', '%' . $this->search . '%')
                      ->orWhere('ip_address', 'like', '%' . $this->search . '%')
                      ->orWhere('mac_address', 'like', '%' . $this->search . '%')
                      ->orWhere('hostname', 'like', '%' . $this->search . '%')
                      ->orWhereHas('equipment', function($eq) {
                          $eq->where('inv_number', 'like', '%' . $this->search . '%')
                             ->orWhere('account_name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when(!empty($this->filterStatus), function($q) {
                $q->whereIn('status', $this->filterStatus);
            })
            ->when(!empty($this->filterBaseComponent), function($q) {
                $q->whereIn('base_component_id', $this->filterBaseComponent);
            })
            ->when(empty($this->search) && empty($this->filterStatus) && empty($this->filterBaseComponent), function($q) {
                $q->whereNull('parent_asset_id');
            });

        if (in_array($this->sortField, ['id', 'serial_number', 'status', 'ip_address', 'mac_address'])) {
            $query->orderBy($this->sortField, $this->sortDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        return view('livewire.admin.asset-manager', [
            'assets' => $query->paginate(15),
            'equipmentList' => Equipment::all(),
            'baseComponentsList' => BaseComponent::all(),
            'modelsList' => EquipmentType::with('brand')->get(),
            'locationsList' => Location::all(),
            'holdersList' => LocationHolder::with(['employee', 'organization'])->get(),
            'parentAssetsList' => Asset::with(['componentType', 'equipment'])->get(),
            'nomenclaturesList' => LowValueMaterial::all(),
            'writeOffActsList' => LowValueWriteOffAct::all(),
        ]);
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
        if ($this->isFilterOpen) {
            $this->isFilterOpen = false;
        }
        if ($this->isViewOpen) {
            $this->isViewOpen = false;
            $this->viewAsset = null;
        }
    }

    private function resetInputFields(){
        $this->assetId = null;
        $this->equipment_id = null;
        $this->base_component_id = null;
        $this->model_id = null;
        $this->current_loc_id = null;
        $this->current_holder_id = null;
        $this->parent_asset_id = null;
        $this->notes = '';
        $this->serial_number = '';
        $this->has_network = 0;
        $this->ip_address = '';
        $this->mac_address = '';
        $this->hostname = '';
        $this->nomenclature_id = null;
        $this->write_off_act_id = null;
        $this->status = 'Працює';
    }

    public function store()
    {
        $this->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'base_component_id' => 'required|exists:base_components,id',
            'model_id' => 'nullable|exists:models_tz,id',
            'current_loc_id' => 'nullable|exists:locations,id',
            'current_holder_id' => 'nullable|exists:location_holders,id',
            'parent_asset_id' => 'nullable|exists:assets,id',
            'notes' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:100',
            'has_network' => 'boolean',
            'ip_address' => 'nullable|ip',
            'mac_address' => 'nullable|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/',
            'hostname' => 'nullable|string|max:100',
            'nomenclature_id' => 'nullable|exists:low_value_materials,id',
            'write_off_act_id' => 'nullable|exists:low_value_write_off_acts,id',
            'status' => 'required|string|max:50',
        ]);

        Asset::updateOrCreate(['id' => $this->assetId], [
            'equipment_id' => $this->equipment_id,
            'base_component_id' => $this->base_component_id,
            'model_id' => $this->model_id ?: null,
            'current_loc_id' => $this->current_loc_id ?: null,
            'current_holder_id' => $this->current_holder_id ?: null,
            'parent_asset_id' => $this->parent_asset_id ?: null,
            'notes' => $this->notes ?: null,
            'serial_number' => $this->serial_number ?: null,
            'ip_address' => $this->ip_address ?: null,
            'mac_address' => $this->mac_address ?: null,
            'hostname' => $this->hostname ?: null,
            'nomenclature_id' => $this->nomenclature_id ?: null,
            'write_off_act_id' => $this->write_off_act_id ?: null,
            'status' => $this->status,
        ]);

        session()->flash('message', 
            $this->assetId ? 'Актив оновлено.' : 'Актив додано.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $comp = Asset::findOrFail($id);
        $this->assetId = $id;
        $this->equipment_id = $comp->equipment_id;
        $this->base_component_id = $comp->base_component_id;
        $this->model_id = $comp->model_id;
        $this->current_loc_id = $comp->current_loc_id;
        $this->current_holder_id = $comp->current_holder_id;
        $this->parent_asset_id = $comp->parent_asset_id;
        $this->notes = $comp->notes;
        $this->serial_number = $comp->serial_number;
        
        $this->has_network = !empty($comp->ip_address) || !empty($comp->mac_address) || !empty($comp->hostname);
        $this->ip_address = $comp->ip_address;
        $this->mac_address = $comp->mac_address;
        $this->hostname = $comp->hostname;

        $this->nomenclature_id = $comp->nomenclature_id;
        $this->write_off_act_id = $comp->write_off_act_id;
        $this->status = $comp->status;
        
        $this->openModal();
    }

    public function delete($id)
    {
        Asset::find($id)->delete();
        session()->flash('message', 'Актив видалено.');
    }
}
