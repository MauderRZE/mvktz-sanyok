<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Models\MaintenanceLog;

class MaintenanceLogForm extends Form
{
    public ?int $logId = null;

    #[Validate('required|exists:assets,id')]
    public ?int $assets_id = null;

    #[Validate('required|date')]
    public string $sent_date = '';

    #[Validate('nullable|date')]
    public string $return_date = '';

    #[Validate('required|string')]
    public string $issue_description = '';

    #[Validate('required|string')]
    public string $status = 'В ремонті';

    public function setLog(MaintenanceLog $log)
    {
        $this->logId = $log->id;
        $this->assets_id = $log->assets_id;
        $this->sent_date = $log->sent_date;
        $this->return_date = $log->return_date ?? '';
        $this->issue_description = $log->issue_description;
        $this->status = $log->status;
    }

    public function store()
    {
        $this->validate();

        MaintenanceLog::updateOrCreate(['id' => $this->logId], [
            'assets_id' => $this->assets_id,
            'sent_date' => $this->sent_date,
            'return_date' => $this->return_date ?: null,
            'issue_description' => $this->issue_description,
            'status' => $this->status,
        ]);

        $isUpdate = $this->logId !== null;
        $this->reset();
        
        return $isUpdate;
    }
}
