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

    public function vendor()
    {
        return $this->belongsTo(Supplier::class, 'vendor_id');
    }

    public function computerSoftwares()
    {
        return $this->hasMany(ComputerSoftware::class, 'license_id');
    }
}
