<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComputerSoftware extends Model
{
    use HasFactory;

    protected $table = 'computer_software';

    public $timestamps = false;

    protected $fillable = [
        'computer_id',
        'software_name',
        'version',
        'is_licensed',
        'license_id',
    ];

    public function computer()
    {
        return $this->belongsTo(Asset::class, 'computer_id');
    }

    public function license()
    {
        return $this->belongsTo(SoftwareLicense::class, 'license_id');
    }
}
