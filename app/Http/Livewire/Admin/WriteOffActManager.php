<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\LowValueWriteOffAct;

class WriteOffActManager extends Component
{
    public $acts, $actId, $act_number, $act_date;
    public $isOpen = 0;

    public function render()
    {
        $this->acts = LowValueWriteOffAct::all();
        return view('livewire.admin.write-off-act-manager')->layout('layouts.admin');
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
    }

    public function store()
    {
        $this->validate([
            'act_number' => 'required|string|max:45',
            'act_date' => 'required|date',
        ]);

        LowValueWriteOffAct::updateOrCreate(['id' => $this->actId], [
            'act_number' => $this->act_number,
            'act_date' => $this->act_date,
        ]);

        session()->flash('message', 
            $this->actId ? 'Акт списання малоцінки оновлено.' : 'Акт списання малоцінки створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $act = LowValueWriteOffAct::findOrFail($id);
        $this->actId = $id;
        $this->act_number = $act->act_number;
        $this->act_date = $act->act_date;
        $this->openModal();
    }

    public function delete($id)
    {
        LowValueWriteOffAct::find($id)->delete();
        session()->flash('message', 'Акт списання малоцінки видалено.');
    }
}
