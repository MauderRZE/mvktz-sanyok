<?php

namespace App\Http\Livewire\Admin;

use App\Livewire\Forms\LocationForm;
use App\Models\Location;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class LocationManager extends Component
{
    use WithPagination;

    public LocationForm $form;

    public $isOpen = 0;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Location::when($this->search, function ($q) {
            $q->where('room_number', 'like', '%'.$this->search.'%');
        })
            ->orderBy('id', 'desc');

        return view('livewire.admin.location-manager', [
            'locations' => $query->paginate(15),
        ]);
    }

    public function resetFilters()
    {
        $this->search = '';
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
