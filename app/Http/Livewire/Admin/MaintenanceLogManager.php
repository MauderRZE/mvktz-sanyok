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

    public $search = '';
    public $filterStatus = [];
    public $filterAsset = [];

    public function render()
    {
        $query = MaintenanceLog::with(['asset.equipment', 'asset.componentType'])
            ->when($this->search, function($q) {
                $search = '%' . $this->search . '%';
                $q->where(function($sub) use ($search) {
                    $sub->where('issue_description', 'like', $search)
                        ->orWhereHas('asset.equipment', function($eq) use ($search) {
                            $eq->where('inv_number', 'like', $search)
                               ->orWhere('account_name', 'like', $search);
                        })
                        ->orWhereHas('asset.componentType', function($ct) use ($search) {
                            $ct->where('component_name', 'like', $search);
                        });
                });
            })
            ->when(!empty($this->filterStatus), function($q) {
                $q->whereIn('status', $this->filterStatus);
            })
            ->when(!empty($this->filterAsset), function($q) {
                $q->whereIn('assets_id', $this->filterAsset);
            })
            ->orderBy('id', 'desc');

        $this->logs = $query->get();
        $this->assetsList = \App\Models\Asset::with(['equipment', 'componentType'])->get();
        return view('livewire.admin.maintenance-log-manager');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatus = [];
        $this->filterAsset = [];
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
            'assets_id' => 'required|exists:assets,id',
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
