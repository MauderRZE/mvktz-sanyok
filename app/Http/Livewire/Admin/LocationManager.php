<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Location;

#[Layout('layouts.admin')]
class LocationManager extends Component
{
    public $locations, $locationId, $room_number;
    public $isOpen = 0;

    public function render()
    {
        $this->locations = Location::all();
        return view('livewire.admin.location-manager');
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
        $this->locationId = null;
        $this->room_number = '';
    }

    public function store()
    {
        $this->validate([
            'room_number' => 'required|unique:locations,room_number,' . $this->locationId,
        ]);

        Location::updateOrCreate(['id' => $this->locationId], [
            'room_number' => $this->room_number
        ]);

        session()->flash('message', 
            $this->locationId ? 'Локацію оновлено.' : 'Локацію створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $loc = Location::findOrFail($id);
        $this->locationId = $id;
        $this->room_number = $loc->room_number;
        $this->openModal();
    }

    public function delete($id)
    {
        Location::find($id)->delete();
        session()->flash('message', 'Локацію видалено.');
    }
}
