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
        'material_id',
        'brand_model',
        'equipment_id',
        'serial_number',
        'purchase_date',
        'installation_date',
        'quantity',
        'notes',
        'status',
        'nomenclature_number',
    ];

    public function getMaterialIdAttribute()
    {
        return $this->id;
    }

    public function setMaterialIdAttribute($value)
    {
        $baseMat = BaseMaterial::find($value);
        if ($baseMat) {
            $this->material_account_name = $baseMat->material_account_name;
        }
    }

    public function getBrandModelAttribute()
    {
        return null;
    }

    public function setBrandModelAttribute($value)
    {
        // Ignore.
    }

    public function getEquipmentIdAttribute()
    {
        return null;
    }

    public function setEquipmentIdAttribute($value)
    {
        // Ignore.
    }

    public function getSerialNumberAttribute()
    {
        return null;
    }

    public function setSerialNumberAttribute($value)
    {
        // Ignore.
    }

    public function getPurchaseDateAttribute()
    {
        return null;
    }

    public function setPurchaseDateAttribute($value)
    {
        // Ignore.
    }

    public function getInstallationDateAttribute()
    {
        return null;
    }

    public function setInstallationDateAttribute($value)
    {
        // Ignore.
    }

    public function getQuantityAttribute()
    {
        return $this->count;
    }

    public function setQuantityAttribute($value)
    {
        $this->count = $value;
    }

    public function getNotesAttribute()
    {
        return null;
    }

    public function setNotesAttribute($value)
    {
        // Ignore.
    }

    public function getStatusAttribute()
    {
        return null;
    }

    public function setStatusAttribute($value)
    {
        // Ignore.
    }

    public function getNomenclatureNumberAttribute()
    {
        return $this->nomenklature_number;
    }

    public function setNomenclatureNumberAttribute($value)
    {
        $this->nomenklature_number = $value;
    }

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
