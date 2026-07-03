<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LowValueWriteOffAct extends Model
{
    use HasFactory;

    protected $table = 'low_value_write_off_acts';

    public $timestamps = false;

    protected $fillable = [
        'act_number',
        'act_date',
    ];

    public function assets()
    {
        return $this->hasMany(EquipmentComponent::class, 'write_off_act_id');
    }
}
