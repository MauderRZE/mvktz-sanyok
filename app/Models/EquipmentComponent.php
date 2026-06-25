<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentComponent extends Model
{
    use HasFactory;

    protected $table = 'equipment_components';

    public $timestamps = false;

    protected $fillable = [
        'equipment_id',
        'component_type_id',
        'brand_model',
        'serial_number',
        'cartridge_model',
        'has_network',
        'ip_address',
        'mac_address',
        'status',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    public function componentType()
    {
        return $this->belongsTo(BaseComponent::class, 'component_type_id');
    }
}
