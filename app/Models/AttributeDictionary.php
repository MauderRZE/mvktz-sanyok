<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeDictionary extends Model
{
    use HasFactory;

    protected $table = 'attributes_dictionary';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function itemProperties()
    {
        return $this->hasMany(ItemProperty::class, 'attribute_id');
    }
}
