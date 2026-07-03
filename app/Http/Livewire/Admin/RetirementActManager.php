<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\EquipmentRetirementAct;

#[Layout('layouts.admin')]
class RetirementActManager extends Component
{
    public $acts, $actId, $act_number, $act_date, $reason;
    public $isOpen = 0;

    public function render()
    {
        $this->acts = EquipmentRetirementAct::all();
        return view('livewire.admin.retirement-act-manager');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->act_date = date('Y-m-d');
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
        $this->actId = null;
        $this->act_number = '';
        $this->act_date = '';
        $this->reason = '';
    }

    public function store()
    {
        $this->validate([
            'act_number' => 'required|string|max:100',
            'act_date' => 'required|date',
            'reason' => 'nullable|string',
        ]);

        EquipmentRetirementAct::updateOrCreate(['id' => $this->actId], [
            'act_number' => $this->act_number,
            'act_date' => $this->act_date,
            'reason' => $this->reason ?: null,
        ]);

        session()->flash('message', 
            $this->actId ? 'Акт списання оновлено.' : 'Акт списання створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $act = EquipmentRetirementAct::findOrFail($id);
        $this->actId = $id;
        $this->act_number = $act->act_number;
        $this->act_date = $act->act_date;
        $this->reason = $act->reason;
        $this->openModal();
    }

    public function delete($id)
    {
        EquipmentRetirementAct::find($id)->delete();
        session()->flash('message', 'Акт списання видалено.');
    }
}
