<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    public $timestamps = false;

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'position',
        'department',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->last_name} {$this->first_name} {$this->middle_name}";
    }

    public function equipmentMovements()
    {
        return $this->hasMany(EquipmentMovement::class, 'employee_id');
    }
}
