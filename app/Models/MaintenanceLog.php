<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceLog extends Model
{
    use HasFactory;

    protected $table = 'maintenance_log';

    public $timestamps = false;

    protected $fillable = [
        'equipment_id',
        'action_type_id',
        'action_date',
        'description',
        'cost',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    public function maintenanceType()
    {
        return $this->belongsTo(MaintenanceType::class, 'action_type_id');
    }
}
