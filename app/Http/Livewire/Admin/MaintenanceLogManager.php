<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\MaintenanceLog;
use App\Models\Equipment;

#[Layout('layouts.admin')]
class MaintenanceLogManager extends Component
{
    public $logs, $logId, $equipment_id, $action_type_id, $action_date, $description, $cost = 0;
    public $equipmentList = [];
    public $isOpen = 0;

    public function render()
    {
        $this->logs = MaintenanceLog::with(['equipment'])->get();
        $this->equipmentList = Equipment::all();
        return view('livewire.admin.maintenance-log-manager');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->action_date = date('Y-m-d');
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
        $this->logId = null;
        $this->equipment_id = null;
        $this->action_type_id = null;
        $this->action_date = '';
        $this->description = '';
        $this->cost = 0;
    }

    public function store()
    {
        $this->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'action_type_id' => 'nullable|integer',
            'action_date' => 'required|date',
            'description' => 'required|string',
            'cost' => 'nullable|numeric|min:0',
        ]);

        MaintenanceLog::updateOrCreate(['id' => $this->logId], [
            'equipment_id' => $this->equipment_id,
            'action_type_id' => $this->action_type_id,
            'action_date' => $this->action_date,
            'description' => $this->description,
            'cost' => $this->cost ?: 0,
        ]);

        session()->flash('message', 
            $this->logId ? 'Запис ТО оновлено.' : 'Роботу ТО зареєстровано.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $log = MaintenanceLog::findOrFail($id);
        $this->logId = $id;
        $this->equipment_id = $log->equipment_id;
        $this->action_type_id = $log->action_type_id;
        $this->action_date = $log->action_date;
        $this->description = $log->description;
        $this->cost = $log->cost;
        $this->openModal();
    }

    public function delete($id)
    {
        MaintenanceLog::find($id)->delete();
        session()->flash('message', 'Запис ТО видалено.');
    }
}
