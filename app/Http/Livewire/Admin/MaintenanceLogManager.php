<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\MaintenanceLog;
use App\Models\Equipment;

#[Layout('layouts.admin')]
class MaintenanceLogManager extends Component
{
    public $logs, $logId, $assets_id, $sent_date, $return_date, $issue_description, $status = 'В ремонті';
    public $assetsList = [];
    public $isOpen = 0;

    public function render()
    {
        $this->logs = MaintenanceLog::with(['asset.equipment', 'asset.componentType'])->get();
        $this->assetsList = \App\Models\Asset::with(['equipment', 'componentType'])->get();
        return view('livewire.admin.maintenance-log-manager');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->sent_date = date('Y-m-d');
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
        $this->assets_id = null;
        $this->sent_date = '';
        $this->return_date = '';
        $this->issue_description = '';
        $this->status = 'В ремонті';
    }

    public function store()
    {
        $this->validate([
            'assets_id' => 'required|exists:equipment_components,id',
            'sent_date' => 'required|date',
            'return_date' => 'nullable|date',
            'issue_description' => 'required|string',
            'status' => 'required|string',
        ]);

        MaintenanceLog::updateOrCreate(['id' => $this->logId], [
            'assets_id' => $this->assets_id,
            'sent_date' => $this->sent_date,
            'return_date' => $this->return_date ?: null,
            'issue_description' => $this->issue_description,
            'status' => $this->status,
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
        $this->assets_id = $log->assets_id;
        $this->sent_date = $log->sent_date;
        $this->return_date = $log->return_date;
        $this->issue_description = $log->issue_description;
        $this->status = $log->status;
        $this->openModal();
    }

    public function delete($id)
    {
        MaintenanceLog::find($id)->delete();
        session()->flash('message', 'Запис ТО видалено.');
    }
}
