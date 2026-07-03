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
        'department',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->last_name} {$this->first_name} {$this->middle_name}";
    }

    public function getDepartmentAttribute()
    {
        return $this->departmentRelationship ? $this->departmentRelationship->name : null;
    }

    public function setDepartmentAttribute($value)
    {
        if (empty($value)) {
            $this->department_id = null;
        } else {
            $dept = Department::firstOrCreate(['name' => $value]);
            $this->department_id = $dept->id;
        }
    }

    public function departmentRelationship()
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
