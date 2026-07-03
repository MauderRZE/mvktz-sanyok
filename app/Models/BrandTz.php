<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandTz extends Model
{
    use HasFactory;

    protected $table = 'brands_tz';

    public $timestamps = false;

    protected $fillable = [
        'brandtz_name',
    ];

    public function equipmentTypes()
    {
        return $this->hasMany(EquipmentType::class, 'brand_id');
    }
}
