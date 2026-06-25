<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeRequirement extends Model
{
    use HasFactory;

    protected $table = 'type_requirements';

    public $timestamps = false;

    protected $fillable = [
        'equipment_type_id',
        'component_id',
    ];

    public function equipmentType()
    {
        return $this->belongsTo(EquipmentType::class, 'equipment_type_id');
    }

    public function component()
    {
        return $this->belongsTo(BaseComponent::class, 'component_id');
    }
}
