<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    public $timestamps = false;

    protected $fillable = [
        'supplier_name',
        'supplier_type_id',
        'tax_code',
    ];

    public function supplierType()
    {
        return $this->belongsTo(SupplierType::class, 'supplier_type_id');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'supplier_id');
    }
}
