<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentType extends Model
{
    use HasFactory;

    protected $table = 'equipment_types';

    public $timestamps = false;

    protected $fillable = [
        'type_name',
    ];

    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'equipment_type_id');
    }
}
