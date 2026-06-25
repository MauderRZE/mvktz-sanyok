<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseComponent extends Model
{
    use HasFactory;

    protected $table = 'base_components';

    public $timestamps = false;

    protected $fillable = [
        'component_name',
    ];
}
