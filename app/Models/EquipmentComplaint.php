<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentComplaint extends Model
{
    use HasFactory;

    protected $table = 'equipment_complaints';

    public $timestamps = false;

    protected $fillable = [
        'equipment_id',
        'complaint_date',
        'reported_by_employee_id',
        'issue_description',
        'resolution_status',
        'resolution_date',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'reported_by_employee_id');
    }
}
