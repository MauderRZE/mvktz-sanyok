<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Models\Organization;

class OrganizationForm extends Form
{
    public ?int $orgId = null;

    public string $org_name = '';

    #[Validate('required|string|max:100')]
    public string $org_type = 'Стороння';

    public function setOrganization(Organization $org)
    {
        $this->orgId = $org->id;
        $this->org_name = $org->org_name;
        $this->org_type = $org->org_type ?? 'Стороння';
    }

    public function store()
    {
        $this->validate([
            'org_name' => 'required|unique:organizations,org_name,' . $this->orgId,
        ]);

        Organization::updateOrCreate(['id' => $this->orgId], [
            'org_name' => $this->org_name,
            'org_type' => $this->org_type
        ]);

        $isUpdate = $this->orgId !== null;
        $this->reset();
        
        return $isUpdate;
    }
}
