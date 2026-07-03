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

    public function type()
    {
        // Table equipment no longer has equipment_type_id.
        // We use a dummy self-referential id relationship to avoid SQL exceptions during eager loading.
        return $this->belongsTo(EquipmentType::class, 'id', 'id');
    }

    public function components()
    {
        return $this->hasMany(EquipmentComponent::class, 'equipment_id');
    }

    public function movements()
    {
        return $this->hasMany(EquipmentMovement::class, 'equip_id');
    }

    public function complaints()
    {
        // Table equipment_complaints does not exist. We use repairs as a fallback to avoid crashes.
        return $this->hasMany(EquipmentComplaint::class, 'assets_id', 'id');
    }

    public function maintenanceLogs()
    {
        // Repairs are now linked to assets (EquipmentComponent), which are linked to equipment.
        return $this->hasManyThrough(
            MaintenanceLog::class,
            EquipmentComponent::class,
            'equipment_id', // Foreign key on assets table...
            'assets_id',    // Foreign key on repairs table...
            'id',           // Local key on equipment table...
            'id'            // Local key on assets table...
        );
    }

    public function softwareLicenses()
    {
        // Licenses are linked to assets (EquipmentComponent).
        return $this->hasManyThrough(
            SoftwareLicense::class,
            EquipmentComponent::class,
            'equipment_id',
            'id',
            'id',
            'id'
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
