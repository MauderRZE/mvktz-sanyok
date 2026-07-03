<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentComponent extends Model
{
    use HasFactory;

    protected $table = 'assets';

    public $timestamps = false;

    protected $fillable = [
        'base_component_id',
        'model_id',
        'serial_number',
        'current_loc_id',
        'current_holder_id',
        'equipment_id',
        'parent_asset_id',
        'notes',
        'ip_address',
        'mac_address',
        'hostname',
        'nomenclature_id',
        'status',
        'write_off_act_id',
        'component_type_id',
        'brand_model',
        'cartridge_model',
        'has_network',
    ];

    public function getComponentTypeIdAttribute()
    {
        return $this->base_component_id;
    }

    public function setComponentTypeIdAttribute($value)
    {
        $this->base_component_id = $value;
    }

    public function getBrandModelAttribute()
    {
        return $this->notes;
    }

    public function setBrandModelAttribute($value)
    {
        $this->notes = $value;
    }

    public function getCartridgeModelAttribute()
    {
        return null;
    }

    public function setCartridgeModelAttribute($value)
    {
        // Ignore or store elsewhere.
    }

    public function getHasNetworkAttribute()
    {
        return !empty($this->ip_address) || !empty($this->mac_address);
    }

    public function setHasNetworkAttribute($value)
    {
        // Ignore.
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    public function componentType()
    {
        return $this->belongsTo(BaseComponent::class, 'base_component_id');
    }

    public function baseComponent()
    {
        return $this->belongsTo(BaseComponent::class, 'base_component_id');
    }

    public function model()
    {
        return $this->belongsTo(EquipmentType::class, 'model_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'current_loc_id');
    }

    public function holder()
    {
        return $this->belongsTo(LocationHolder::class, 'current_holder_id');
    }

    public function parentAsset()
    {
        return $this->belongsTo(EquipmentComponent::class, 'parent_asset_id');
    }

    public function childAssets()
    {
        return $this->hasMany(EquipmentComponent::class, 'parent_asset_id');
    }

    public function lowValueMaterial()
    {
        return $this->belongsTo(LowValueMaterial::class, 'nomenclature_id');
    }

    public function writeOffAct()
    {
        return $this->belongsTo(LowValueWriteOffAct::class, 'write_off_act_id');
    }

    public function computerSoftwares()
    {
        return $this->hasMany(ComputerSoftware::class, 'computer_id');
    }

    public function repairs()
    {
        return $this->hasMany(MaintenanceLog::class, 'assets_id');
    }
}
