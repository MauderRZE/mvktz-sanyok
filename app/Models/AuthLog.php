<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthLog extends Model
{
    protected $connection = 'sqlite_history';
    protected $table = 'auth_logs';
    public $timestamps = false; // We only have created_at

    protected $fillable = [
        'user_id',
        'event',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        // Connection needs to know this belongs to the main DB
        return $this->belongsTo(User::class);
    }
}
