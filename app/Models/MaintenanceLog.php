<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceLog extends Model
{
    use HasFactory;

    protected $table = 'repairs';

    public $timestamps = false;

    protected $fillable = [
        'assets_id',
        'sent_date',
        'return_date',
        'issue_description',
        'status',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'assets_id');
    }

}
