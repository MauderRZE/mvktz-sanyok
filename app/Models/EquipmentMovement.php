<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentMovement extends Model
{
    use HasFactory;

    protected $table = 'movements';

    public $timestamps = false;

    protected $fillable = [
        'equip_id',
        'asset_id',
        'from_holder_id',
        'to_holder_id',
        'employee_id',
        'action_date',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equip_id');
    }

    public function asset()
    {
        return $this->belongsTo(EquipmentComponent::class, 'asset_id');
    }

    public function fromHolder()
    {
        return $this->belongsTo(LocationHolder::class, 'from_holder_id');
    }

    public function toHolder()
    {
        return $this->belongsTo(LocationHolder::class, 'to_holder_id');
    }

    public function location()
    {
        // Table movements does not have location_id anymore. 
        // We use a dummy self-referential relationship to avoid SQL exceptions during eager loading.
        return $this->belongsTo(Location::class, 'id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
