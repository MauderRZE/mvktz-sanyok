<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\MaintenanceLog;
use App\Models\Equipment;
use App\Livewire\Forms\MaintenanceLogForm;

#[Layout('layouts.admin')]
class MaintenanceLogManager extends Component
{
    public MaintenanceLogForm $form;
    
    public $logs;
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
        $this->form->reset();
        $this->form->sent_date = date('Y-m-d');
        $this->form->status = 'В ремонті';
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

    public function store()
    {
        $isUpdate = $this->form->store();

        session()->flash('message', 
            $isUpdate ? 'Запис ТО оновлено.' : 'Роботу ТО зареєстровано.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $log = MaintenanceLog::findOrFail($id);
        $this->form->setLog($log);
        $this->openModal();
    }

    public function delete($id)
    {
        MaintenanceLog::find($id)->delete();
        session()->flash('message', 'Запис ТО видалено.');
    }
}
