<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipment';

    // The tables in the database don't have created_at and updated_at
    public $timestamps = false;

    protected $fillable = [
        'inventory_number',
        'accounting_name',
        'equipment_type_id',
        'status',
    ];

    public function type()
    {
        return $this->belongsTo(EquipmentType::class, 'equipment_type_id');
    }

    public function components()
    {
        return $this->hasMany(EquipmentComponent::class, 'equipment_id');
    }

    public function movements()
    {
        return $this->hasMany(EquipmentMovement::class, 'equipment_id');
    }

    public function complaints()
    {
        return $this->hasMany(EquipmentComplaint::class, 'equipment_id');
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class, 'equipment_id');
    }
}
