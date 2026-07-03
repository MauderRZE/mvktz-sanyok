<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePhone extends Model
{
    use HasFactory;

    protected $table = 'employee_phones';

    public $timestamps = false;

    protected $fillable = [
        'employee_id',
        'phone_number',
        'phone_type',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
