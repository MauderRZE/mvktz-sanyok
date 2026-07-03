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
        'material_account_name',
        'price',
        'count',
        'nomenklature_number',
        'contract_id',
    ];

    public function material()
    {
        // Table low_value_materials does not have material_id anymore.
        // We use a dummy self-referential relationship to avoid SQL exceptions during eager loading.
        return $this->belongsTo(BaseMaterial::class, 'id', 'id');
    }

    public function equipment()
    {
        // Table low_value_materials does not have equipment_id anymore.
        // We use a dummy self-referential relationship to avoid SQL exceptions during eager loading.
        return $this->belongsTo(Equipment::class, 'id', 'id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }
}
