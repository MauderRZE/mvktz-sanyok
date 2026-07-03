<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentComplaint extends Model
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
        'complaint_date',
        'reported_by_employee_id',
        'resolution_status',
        'resolution_date',
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

    public function getComplaintDateAttribute()
    {
        return $this->sent_date;
    }

    public function setComplaintDateAttribute($value)
    {
        $this->sent_date = $value;
    }

    public function getReportedByEmployeeIdAttribute()
    {
        return null;
    }

    public function setReportedByEmployeeIdAttribute($value)
    {
        // Ignore employee_id since not present in repairs table.
    }

    public function getResolutionStatusAttribute()
    {
        return $this->status === 'Повернено' ? 'Вирішено' : 'Відкрито';
    }

    public function setResolutionStatusAttribute($value)
    {
        $this->status = ($value === 'Вирішено') ? 'Повернено' : 'В ремонті';
    }

    public function getResolutionDateAttribute()
    {
        return $this->return_date;
    }

    public function setResolutionDateAttribute($value)
    {
        $this->return_date = $value;
    }

    public function equipment()
    {
        // Fallback relationship to prevent eager loading errors.
        return $this->belongsTo(Equipment::class, 'assets_id', 'id');
    }

    public function asset()
    {
        return $this->belongsTo(EquipmentComponent::class, 'assets_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id', 'id');
    }
}
