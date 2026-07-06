<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationHolder extends Model
{
    use HasFactory;

    protected $table = 'location_holders';

    public $timestamps = false;

    protected $fillable = [
        'employee_id',
        'organization_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'current_holder_id');
    }
}
