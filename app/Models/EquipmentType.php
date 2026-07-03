<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentType extends Model
{
    use HasFactory;

    protected $table = 'models_tz';

    public $timestamps = false;

    protected $fillable = [
        'brand_id',
        'model_name',
    ];

    public function brand()
    {
        return $this->belongsTo(BrandTz::class, 'brand_id');
    }

    public function category()
    {
        // Table models_tz does not have category_id.
        // We use a dummy self-referential relationship to avoid SQL exceptions during eager loading.
        return $this->belongsTo(EquipmentCategory::class, 'id', 'id');
    }

    public function assets()
    {
        return $this->hasMany(EquipmentComponent::class, 'model_id');
    }
}
