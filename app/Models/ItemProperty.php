<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemProperty extends Model
{
    use HasFactory;

    protected $table = 'item_properties';

    public $timestamps = false;

    protected $fillable = [
        'asset_id',
        'nomenclature_id',
        'attribute_id',
        'attr_value',
    ];

    public function asset()
    {
        return $this->belongsTo(EquipmentComponent::class, 'asset_id');
    }

    public function nomenclature()
    {
        return $this->belongsTo(LowValueMaterial::class, 'nomenclature_id');
    }

    public function attribute()
    {
        return $this->belongsTo(AttributeDictionary::class, 'attribute_id');
    }
}
