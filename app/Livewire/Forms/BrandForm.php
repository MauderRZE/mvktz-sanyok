<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Models\BrandTz;

class BrandForm extends Form
{
    public ?int $brandId = null;

    public string $brandtz_name = '';

    public function setBrand(BrandTz $brand)
    {
        $this->brandId = $brand->id;
        $this->brandtz_name = $brand->brandtz_name;
    }

    public function store()
    {
        $this->validate([
            'brandtz_name' => 'required|unique:brands_tz,brandtz_name,' . $this->brandId,
        ]);

        BrandTz::updateOrCreate(['id' => $this->brandId], [
            'brandtz_name' => $this->brandtz_name
        ]);

        $isUpdate = $this->brandId !== null;
        $this->reset();
        
        return $isUpdate;
    }
}
