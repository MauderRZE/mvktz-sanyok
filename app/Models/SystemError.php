<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemError extends Model
{
    protected $connection = 'sqlite_errors';
    protected $table = 'system_errors';

    protected $fillable = [
        'page_type',
        'error_type',
        'error_text',
        'is_resolved'
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
    ];
}
