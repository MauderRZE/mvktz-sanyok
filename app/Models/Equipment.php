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
        'inv_number',
        'account_name',
        'buy_price',
        'purchase_id',
        'status',
        'retirement_act_id',
        'notes',
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class, 'equipment_id');
    }

    public function movements()
    {
        return $this->hasMany(EquipmentMovement::class, 'equip_id');
    }


    public function maintenanceLogs()
    {
        // Repairs are now linked to assets (Asset), which are linked to equipment.
        return $this->hasManyThrough(
            MaintenanceLog::class,
            Asset::class,
            'equipment_id', // Foreign key on assets table...
            'assets_id',    // Foreign key on repairs table...
            'id',           // Local key on equipment table...
            'id'            // Local key on assets table...
        );
    }


    public function lowValueMaterials()
    {
        return $this->hasMany(LowValueMaterial::class, 'contract_id', 'purchase_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Contract::class, 'purchase_id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'purchase_id');
    }

    public function retirementAct()
    {
        return $this->belongsTo(EquipmentRetirementAct::class, 'retirement_act_id');
    }
}
