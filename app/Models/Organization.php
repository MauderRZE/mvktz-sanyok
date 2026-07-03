<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organizations';

    public $timestamps = false;

    protected $fillable = [
        'org_name',
        'org_type',
    ];

    public function locationHolders()
    {
        return $this->hasMany(LocationHolder::class, 'organization_id');
    }
}
