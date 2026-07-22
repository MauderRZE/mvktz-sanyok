<?php

namespace App\Http\Livewire\Admin;

use App\Livewire\Forms\ComputerSoftwareForm;
use App\Models\Asset;
use App\Models\ComputerSoftware;
use App\Models\SoftwareLicense;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class ComputerSoftwareManager extends Component
{
    public ComputerSoftwareForm $form;

    public $isOpen = false;

    public $search = '';

    public $filterSoftwareName = [];

    public $filterIsLicensed = '';

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
                $q->whereIn('software_name', $this->filterSoftwareName);
            })
            ->when($this->filterIsLicensed !== '', function ($q) {
                $q->where('is_licensed', $this->filterIsLicensed);
            })
            ->orderBy('id', 'desc');

        $software = $query->get();

        $computers = $this->isOpen ? Asset::with(['componentType', 'equipment'])->get() : collect();
        $licenses = $this->isOpen ? SoftwareLicense::all() : collect();

        return view('livewire.admin.computer-software-manager', compact('software', 'computers', 'licenses'));
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterSoftwareName = [];
        $this->filterIsLicensed = '';
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
