<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Location;
use App\Livewire\Forms\LocationForm;

#[Layout('layouts.admin')]
class LocationManager extends Component
{
    public LocationForm $form;
    
    public $locations;
    public $isOpen = 0;

    public $search = '';

    public function render()
    {
        $this->locations = Location::when($this->search, function($q) {
            $q->where('room_number', 'like', '%' . $this->search . '%');
        })
        ->orderBy('id', 'desc')
        ->get();
        return view('livewire.admin.location-manager');
    }

    public function resetFilters()
    {
        $this->search = '';
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
            $isUpdate ? 'Локацію оновлено.' : 'Локацію створено.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $loc = Location::findOrFail($id);
        $this->form->setLocation($loc);
        $this->openModal();
    }

    public function delete($id)
    {
        Location::find($id)->delete();
        session()->flash('message', 'Локацію видалено.');
    }
}
