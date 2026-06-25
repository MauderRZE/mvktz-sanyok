<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Equipment;
use App\Models\EquipmentType;

class EquipmentManager extends Component
{
    use \Livewire\WithPagination;

    public $equipmentId, $inventory_number, $accounting_name, $equipment_type_id, $status;
    public $types;
    public $isOpen = 0;
    
    public $isViewOpen = false;
    public $viewEquipmentId = null;
    public $viewEquipment = null;

    // Filters & Sorting
    public $search = '';
    public $filterType = '';
    public $filterStatus = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';

    public function updatingSearch()
    {
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
        
        $query = Equipment::with('type')
            ->when($this->search, function($q) {
                $q->where('inventory_number', 'like', '%' . $this->search . '%')
                  ->orWhere('accounting_name', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterType, function($q) {
                $q->where('equipment_type_id', $this->filterType);
            })
            ->when($this->filterStatus, function($q) {
                $q->where('status', $this->filterStatus);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.admin.equipment-manager', [
            'equipments' => $query->paginate(15)
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
    }

    public function store()
    {
        $this->validate([
            'inventory_number' => 'required',
            'accounting_name' => 'required',
            'equipment_type_id' => 'required|integer',
            'status' => 'required',
        ]);

        Equipment::updateOrCreate(['id' => $this->equipmentId], [
            'inventory_number' => $this->inventory_number,
            'accounting_name' => $this->accounting_name,
            'equipment_type_id' => $this->equipment_type_id,
            'status' => $this->status
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
        $this->openModal();
    }

    public function view($id)
    {
        $this->viewEquipmentId = $id;
        $this->viewEquipment = Equipment::with(['type', 'components.componentType', 'movements.location', 'movements.employee', 'complaints', 'maintenanceLogs'])->findOrFail($id);
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
