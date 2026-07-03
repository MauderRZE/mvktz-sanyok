<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwareLicense extends Model
{
    use HasFactory;

    protected $table = 'licenses';

    public $timestamps = false;

    protected $fillable = [
        'vendor_id',
        'license_name',
        'license_type',
        'purchase_date',
        'component_id',
        'software_name',
        'license_key',
        'license_status',
        'expiration_date',
    ];

    public function getSoftwareNameAttribute()
    {
        return $this->license_name;
    }

    public function setSoftwareNameAttribute($value)
    {
        $this->license_name = $value;
    }

    public function getComponentIdAttribute()
    {
        return $this->id;
    }

    public function setComponentIdAttribute($value)
    {
        // Ignore component_id since not present in licenses table.
    }

    public function getLicenseKeyAttribute()
    {
        return null;
    }

    public function setLicenseKeyAttribute($value)
    {
        // Ignore.
    }

    public function getLicenseStatusAttribute()
    {
        return 'Активна';
    }

    public function setLicenseStatusAttribute($value)
    {
        // Ignore.
    }

    public function getExpirationDateAttribute()
    {
        return null;
    }

    public function setExpirationDateAttribute($value)
    {
        // Ignore.
    }

    public function component()
    {
        // Table licenses does not link directly to components.
        // We use a dummy self-referential relationship to avoid SQL exceptions during eager loading.
        return $this->belongsTo(EquipmentComponent::class, 'id', 'id');
    }
}
