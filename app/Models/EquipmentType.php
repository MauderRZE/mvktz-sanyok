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
        'base_component_id',

    ];

    public function brand()
    {
        return $this->belongsTo(BrandTz::class, 'brand_id');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'model_id');
    }

    public function baseComponent()
    {
        return $this->belongsTo(BaseComponent::class, 'base_component_id');
    }
}
