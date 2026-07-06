<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employee';

    public $timestamps = false;

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'position',
        'status',
        'department_id',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->last_name} {$this->first_name} {$this->middle_name}";
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function phones()
    {
        return $this->hasMany(EmployeePhone::class, 'employee_id');
    }

    public function equipmentMovements()
    {
        return $this->hasMany(EquipmentMovement::class, 'employee_id');
    }
}
