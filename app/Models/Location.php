<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $table = 'locations';

    public $timestamps = false;

    protected $fillable = [
        'room_number',
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class, 'current_loc_id');
    }
}
