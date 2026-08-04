<?php

namespace App\Http\Livewire\Admin;

use App\Livewire\Forms\MaintenanceLogForm;
use App\Models\Asset;
use App\Models\MaintenanceLog;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class MaintenanceLogManager extends Component
{
    use WithPagination;

    public MaintenanceLogForm $form;

    public $assetsList = [];

    public $isOpen = 0;

    public $search = '';

    public $filterStatus = [];

    public $filterAsset = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterAsset()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = MaintenanceLog::with(['asset.equipment', 'asset.componentType'])
            ->when($this->search, function ($q) {
                $search = '%'.$this->search.'%';
                $q->where(function ($sub) use ($search) {
                    $sub->where('issue_description', 'like', $search)
                        ->orWhereHas('asset.equipment', function ($eq) use ($search) {
                            $eq->where('inv_number', 'like', $search)
                                ->orWhere('account_name', 'like', $search);
                        })
                        ->orWhereHas('asset.componentType', function ($ct) use ($search) {
                            $ct->where('component_name', 'like', $search);
                        });
                });
            })
            ->when(! empty($this->filterStatus), function ($q) {
                $hasNull = in_array('null', $this->filterStatus, true) || in_array(null, $this->filterStatus, true);
                $statuses = array_filter($this->filterStatus, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($statuses, $hasNull) {
                    if (! empty($statuses)) {
                        $sub->whereIn('status', $statuses);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('status');
                    }
                });
            })
            ->when(! empty($this->filterAsset), function ($q) {
                $hasNull = in_array('null', $this->filterAsset, true) || in_array(null, $this->filterAsset, true);
                $assets = array_filter($this->filterAsset, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($assets, $hasNull) {
                    if (! empty($assets)) {
                        $sub->whereIn('assets_id', $assets);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('assets_id');
                    }
                });
            })
            ->orderBy('id', 'desc');

        $this->assetsList = Asset::with(['equipment', 'componentType'])->get();

        return view('livewire.admin.maintenance-log-manager', [
            'logs' => $query->paginate(15),
        ]);
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatus = [];
        $this->filterAsset = [];
        $this->resetPage();
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
