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
    ];

    public function component()
    {
        // Table licenses does not link directly to components.
        // We use a dummy self-referential relationship to avoid SQL exceptions during eager loading.
        return $this->belongsTo(EquipmentComponent::class, 'id', 'id');
    }
}
