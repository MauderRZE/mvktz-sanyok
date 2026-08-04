<?php

namespace App\Http\Livewire\Admin;

use App\Livewire\Forms\ComputerSoftwareForm;
use App\Models\Asset;
use App\Models\ComputerSoftware;
use App\Models\SoftwareLicense;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ComputerSoftwareManager extends Component
{
    use WithPagination;

    public ComputerSoftwareForm $form;

    public $isOpen = false;

    public $search = '';

    public $filterSoftwareName = [];

    public $filterIsLicensed = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterSoftwareName()
    {
        $this->resetPage();
    }

    public function updatingFilterIsLicensed()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ComputerSoftware::with(['computer.componentType', 'computer.equipment', 'license'])
            ->when($this->search, function ($q) {
                $search = '%'.$this->search.'%';
                $q->where(function ($sub) use ($search) {
                    $sub->where('software_name', 'like', $search)
                        ->orWhere('version', 'like', $search)
                        ->orWhereHas('computer.componentType', function ($ct) use ($search) {
                            $ct->where('component_name', 'like', $search);
                        })
                        ->orWhereHas('computer.equipment', function ($eq) use ($search) {
                            $eq->where('inv_number', 'like', $search);
                        });
                });
            })
            ->when(! empty($this->filterSoftwareName), function ($q) {
                $hasNull = in_array('null', $this->filterSoftwareName, true) || in_array(null, $this->filterSoftwareName, true);
                $names = array_filter($this->filterSoftwareName, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($names, $hasNull) {
                    if (! empty($names)) {
                        $sub->whereIn('software_name', $names);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('software_name');
                    }
                });
            })
            ->when($this->filterIsLicensed !== '', function ($q) {
                $q->where('is_licensed', $this->filterIsLicensed);
            })
            ->orderBy('id', 'desc');

        $software = $query->paginate(15);

        $computersOptions = [];
        $licensesOptions = [];

        if ($this->isOpen) {
            $computersOptions = Asset::with([
                'componentType:id,component_name',
                'equipment:id,inv_number',
            ])
                ->select('id', 'base_component_id', 'equipment_id')
                ->get()
                ->mapWithKeys(fn ($item) => [
                    $item->id => ($item->componentType->component_name ?? 'Асет').' (Inv: '.($item->equipment?->inv_number ?? 'Немає').')',
                ])
                ->toArray();

            $licensesOptions = SoftwareLicense::pluck('license_name', 'id')->toArray();
        }

        return view('livewire.admin.computer-software-manager', compact('software', 'computersOptions', 'licensesOptions'));
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterSoftwareName = [];
        $this->filterIsLicensed = '';
        $this->resetPage();
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

    public function store()
    {
        $isUpdate = $this->form->store();

        session()->flash('message',
            $isUpdate ? 'Запис про ПЗ оновлено.' : 'Запис про ПЗ створено.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $sw = ComputerSoftware::findOrFail($id);
        $this->form->setSoftware($sw);
        $this->openModal();
    }

    public function delete($id)
    {
        ComputerSoftware::findOrFail($id)->delete();
        session()->flash('message', 'Запис про ПЗ видалено.');
    }
}
