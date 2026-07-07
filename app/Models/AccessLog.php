<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    protected $connection = 'sqlite_history';
    protected $table = 'access_logs';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'method',
        'status_code',
        'url',
        'ip_address',
        'user_agent',
        'error_text',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
