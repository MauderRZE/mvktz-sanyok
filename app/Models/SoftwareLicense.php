<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwareLicense extends Model
{
    use HasFactory;

    protected $table = 'software_licenses';

    public $timestamps = false;

    protected $fillable = [
        'component_id',
        'software_name',
        'license_key',
        'license_status',
        'expiration_date',
    ];

    public function component()
    {
        return $this->belongsTo(EquipmentComponent::class, 'component_id');
    }
}
