<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Models\LowValueWriteOffAct;

class WriteOffActForm extends Form
{
    public ?int $actId = null;

    #[Validate('required|string|max:45')]
    public string $act_number = '';

    #[Validate('required|date')]
    public string $act_date = '';

    public function setAct(LowValueWriteOffAct $act)
    {
        $this->actId = $act->id;
        $this->act_number = $act->act_number;
        if ($act->act_date) {
            $this->act_date = is_string($act->act_date) 
                ? substr($act->act_date, 0, 10) 
                : $act->act_date->format('Y-m-d');
        }
    }

    public function store()
    {
        $this->validate();

        LowValueWriteOffAct::updateOrCreate(['id' => $this->actId], [
            'act_number' => $this->act_number,
            'act_date' => $this->act_date,
        ]);

        $isUpdate = $this->actId !== null;
        $this->reset();
        
        return $isUpdate;
    }
}
