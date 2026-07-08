<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Models\EquipmentRetirementAct;

class RetirementActForm extends Form
{
    public ?int $actId = null;

    #[Validate('required|string|max:100')]
    public string $act_number = '';

    #[Validate('required|date')]
    public string $act_date = '';

    #[Validate('nullable|string')]
    public string $reason = '';

    public function setAct(EquipmentRetirementAct $act)
    {
        $this->actId = $act->id;
        $this->act_number = $act->act_number;
        if ($act->act_date) {
            $this->act_date = is_string($act->act_date) 
                ? substr($act->act_date, 0, 10) 
                : $act->act_date->format('Y-m-d');
        }
        $this->reason = $act->reason ?? '';
    }

    public function store()
    {
        $this->validate();

        $act = EquipmentRetirementAct::updateOrCreate(['id' => $this->actId], [
            'act_number' => $this->act_number,
            'act_date' => $this->act_date,
            'reason' => $this->reason ?: null,
        ]);
        
        // Тут, при потребі, можна додати логіку, яка переводить всі прив'язані активи у статус "Списано"
        // (Наприклад, $act->assets()->update(['status' => 'Списано']))

        $isUpdate = $this->actId !== null;
        $this->reset();
        
        return $isUpdate;
    }
}
