<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentRetirementAct extends Model
{
    use HasFactory;

    protected $table = 'equipment_retirement_acts';

    public $timestamps = false;

    protected $fillable = [
        'act_number',
        'act_date',
        'reason',
    ];

    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'retirement_act_id');
    }
}
