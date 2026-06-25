<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseMaterial extends Model
{
    use HasFactory;

    protected $table = 'base_materials';

    public $timestamps = false;

    protected $fillable = [
        'material_name',
    ];
}
