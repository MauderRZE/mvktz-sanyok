<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $table = 'purchases';

    public $timestamps = false;

    protected $fillable = [
        'contract_number',
        'contract_date',
        'supplier_id',
        'contract_link',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function lowValueMaterials()
    {
        return $this->hasMany(LowValueMaterial::class, 'contract_id');
    }
}
