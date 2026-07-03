<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeRequirement extends Model
{
    use HasFactory;

    protected $table = 'models_tz';

    public $timestamps = false;

    protected $fillable = [
        'brand_id',
        'model_name',
    ];

    public function equipmentType()
    {
        // Table type_requirements does not exist.
        // We use a dummy self-referential relationship to avoid SQL exceptions during eager loading.
        return $this->belongsTo(EquipmentType::class, 'id', 'id');
    }

    public function componentType()
    {
        // We use a dummy self-referential relationship to avoid SQL exceptions during eager loading.
        return $this->belongsTo(BaseComponent::class, 'id', 'id');
    }

    public function component()
    {
        return $this->belongsTo(BaseComponent::class, 'id', 'id');
    }
}
