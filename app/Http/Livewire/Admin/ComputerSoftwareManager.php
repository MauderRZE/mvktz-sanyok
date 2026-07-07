<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\ComputerSoftware;
use App\Models\Asset;
use App\Models\SoftwareLicense;

#[Layout('layouts.admin')]
class ComputerSoftwareManager extends Component
{
    public $software, $softwareId, $computer_id, $software_name, $version, $is_licensed = 0, $license_id;
    public $computers, $licenses;
    public $isOpen = false;

    public $search = '';
    public $filterSoftwareName = [];
    public $filterIsLicensed = '';

    public function mount()
    {
        $this->computers = Asset::with(['componentType', 'equipment'])->get();
        $this->licenses = SoftwareLicense::all();
    }

    public function render()
    {
        $query = ComputerSoftware::with(['computer.componentType', 'license'])
            ->when($this->search, function($q) {
                $search = '%' . $this->search . '%';
                $q->where(function($sub) use ($search) {
                    $sub->where('software_name', 'like', $search)
                        ->orWhere('version', 'like', $search)
                        ->orWhereHas('computer.componentType', function($ct) use ($search) {
                            $ct->where('component_name', 'like', $search);
                        })
                        ->orWhereHas('computer.equipment', function($eq) use ($search) {
                            $eq->where('inv_number', 'like', $search);
                        });
                });
            })
            ->when(!empty($this->filterSoftwareName), function($q) {
                $q->whereIn('software_name', $this->filterSoftwareName);
            })
            ->when($this->filterIsLicensed !== '', function($q) {
                $q->where('is_licensed', $this->filterIsLicensed);
            })
            ->orderBy('id', 'desc');

        $this->software = $query->get();
        return view('livewire.admin.computer-software-manager');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterSoftwareName = [];
        $this->filterIsLicensed = '';
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

    private function resetInputFields()
    {
        $this->softwareId = null;
        $this->computer_id = null;
        $this->software_name = '';
        $this->version = '';
        $this->is_licensed = 0;
        $this->license_id = null;
    }

    public function store()
    {
        $this->validate([
            'computer_id' => 'required|exists:assets,id',
            'software_name' => 'required|in:Windows,Office,ESET',
            'version' => 'required|string|max:50',
            'is_licensed' => 'boolean',
            'license_id' => 'nullable|exists:licenses,id',
        ]);

        ComputerSoftware::updateOrCreate(['id' => $this->softwareId], [
            'computer_id' => $this->computer_id,
            'software_name' => $this->software_name,
            'version' => $this->version,
            'is_licensed' => $this->is_licensed ? 1 : 0,
            'license_id' => $this->license_id ?: null,
        ]);

        session()->flash('message', 
            $this->softwareId ? 'Запис про ПЗ оновлено.' : 'Запис про ПЗ створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $sw = ComputerSoftware::findOrFail($id);
        $this->softwareId = $id;
        $this->computer_id = $sw->computer_id;
        $this->software_name = $sw->software_name;
        $this->version = $sw->version;
        $this->is_licensed = $sw->is_licensed;
        $this->license_id = $sw->license_id;
        $this->openModal();
    }

    public function delete($id)
    {
        ComputerSoftware::findOrFail($id)->delete();
        session()->flash('message', 'Запис про ПЗ видалено.');
    }
}
