<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Models\SoftwareLicense;

class SoftwareLicenseForm extends Form
{
    public ?int $licenseId = null;

    #[Validate('nullable|integer')]
    public ?int $vendor_id = null;

    #[Validate('required|string|max:255')]
    public string $license_name = '';

    #[Validate('nullable|string|max:100')]
    public string $license_type = '';

    #[Validate('nullable|string|max:100')]
    public string $custom_license_type = '';

    #[Validate('nullable|date')]
    public string $purchase_date = '';

    public function setLicense(SoftwareLicense $license)
    {
        $this->licenseId = $license->id;
        $this->vendor_id = $license->vendor_id;
        $this->license_name = $license->license_name;
        
        $predefined = ['OEM', 'Retail', 'Корпоративна', 'Підписка', 'Безкоштовна'];
        if (in_array($license->license_type, $predefined) || empty($license->license_type)) {
            $this->license_type = $license->license_type ?? '';
            $this->custom_license_type = '';
        } else {
            $this->license_type = 'Інше';
            $this->custom_license_type = $license->license_type;
        }
        
        $this->purchase_date = $license->purchase_date ?? '';
    }

    public function store()
    {
        $this->validate();

        $type = $this->license_type;
        if ($type === 'Інше') {
            $type = $this->custom_license_type;
        }

        SoftwareLicense::updateOrCreate(['id' => $this->licenseId], [
            'vendor_id' => $this->vendor_id ?: null,
            'license_name' => $this->license_name,
            'license_type' => $type ?: null,
            'purchase_date' => $this->purchase_date ?: null,
        ]);

        $isUpdate = $this->licenseId !== null;
        $this->reset();
        
        return $isUpdate;
    }
}
