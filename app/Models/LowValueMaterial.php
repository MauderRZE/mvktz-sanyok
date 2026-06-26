<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LowValueMaterial extends Model
{
    use HasFactory;

    protected $table = 'low_value_materials';

    public $timestamps = false;

    protected $fillable = [
        'material_id',
        'brand_model',
        'equipment_id',
        'contract_id',
        'serial_number',
        'nomenclature_number',
        'purchase_date',
        'installation_date',
        'quantity',
        'notes',
        'status',
    ];

    public function material()
    {
        return $this->belongsTo(BaseMaterial::class, 'material_id');
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }
}
