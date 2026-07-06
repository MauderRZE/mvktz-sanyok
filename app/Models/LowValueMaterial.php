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

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function itemProperties()
    {
        return $this->hasMany(ItemProperty::class, 'nomenclature_id');
    }
}
