<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceLog extends Model
{
    use HasFactory;

    protected $table = 'repairs';

    public $timestamps = false;

    protected $fillable = [
        'assets_id',
        'sent_date',
        'return_date',
        'issue_description',
        'status',
        'equipment_id',
        'action_type_id',
        'action_date',
        'description',
        'cost',
    ];

    public function getEquipmentIdAttribute()
    {
        return $this->asset ? $this->asset->equipment_id : null;
    }

    public function setEquipmentIdAttribute($value)
    {
        $asset = EquipmentComponent::where('equipment_id', $value)->first();
        if ($asset) {
            $this->assets_id = $asset->id;
        } else {
            $this->assets_id = $value;
        }
    }

    public function getActionDateAttribute()
    {
        return $this->sent_date;
    }

    public function setActionDateAttribute($value)
    {
        $this->sent_date = $value;
    }

    public function getActionTypeIdAttribute()
    {
        return $this->id;
    }

    public function setActionTypeIdAttribute($value)
    {
        // Ignore action_type_id since not present in repairs table.
    }

    public function getDescriptionAttribute()
    {
        return $this->issue_description;
    }

    public function setDescriptionAttribute($value)
    {
        $this->issue_description = $value;
    }

    public function getCostAttribute()
    {
        return 0;
    }

    public function setCostAttribute($value)
    {
        // Ignore cost since not present in repairs table.
    }

    public function equipment()
    {
        // Table repairs links to assets (EquipmentComponent), not equipment directly.
        // We use a dummy self-referential relationship to avoid SQL exceptions during eager loading.
        return $this->belongsTo(Equipment::class, 'assets_id', 'id');
    }

    public function asset()
    {
        return $this->belongsTo(EquipmentComponent::class, 'assets_id');
    }

}
