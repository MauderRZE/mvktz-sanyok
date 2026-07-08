<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Models\Location;

class LocationForm extends Form
{
    public ?int $locationId = null;

    public string $room_number = '';

    public function setLocation(Location $location)
    {
        $this->locationId = $location->id;
        $this->room_number = $location->room_number;
    }

    public function store()
    {
        $this->validate([
            'room_number' => 'required|unique:locations,room_number,' . $this->locationId,
        ]);

        Location::updateOrCreate(['id' => $this->locationId], [
            'room_number' => $this->room_number
        ]);

        $isUpdate = $this->locationId !== null;
        $this->reset();
        
        return $isUpdate;
    }
}
