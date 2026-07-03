<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseMaterial extends Model
{
    use HasFactory;

    protected $table = 'low_value_materials';

    public $timestamps = false;

    protected $fillable = [
        'material_account_name',
    ];

    public function getMaterialNameAttribute()
    {
        return $this->material_account_name;
    }

    public function setMaterialNameAttribute($value)
    {
        $this->material_account_name = $value;
    }
}
