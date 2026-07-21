<?php

namespace App\Livewire\Forms;

use App\Models\ComputerSoftware;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ComputerSoftwareForm extends Form
{
    public ?int $softwareId = null;

    #[Validate('required|exists:assets,id')]
    public ?int $computer_id = null;

    #[Validate('required|in:Windows,Office,ESET')]
    public string $software_name = '';

    #[Validate('required|string|max:50')]
    public string $version = '';

    #[Validate('boolean')]
    public bool $is_licensed = false;

    #[Validate('nullable|exists:licenses,id')]
    public ?int $license_id = null;

    public function setSoftware(ComputerSoftware $sw)
    {
        $this->softwareId = $sw->id;
        $this->computer_id = $sw->computer_id;
        $this->software_name = $sw->software_name;
        $this->version = $sw->version;
        $this->is_licensed = (bool) $sw->is_licensed;
        $this->license_id = $sw->license_id;
    }

    public function store()
    {
        $this->validate();

        ComputerSoftware::updateOrCreate(['id' => $this->softwareId], [
            'computer_id' => $this->computer_id,
            'software_name' => $this->software_name,
            'version' => $this->version,
            'is_licensed' => $this->is_licensed ? 1 : 0,
            'license_id' => $this->license_id ?: null,
        ]);

        $isUpdate = $this->softwareId !== null;
        $this->reset();

        return $isUpdate;
    }
}
