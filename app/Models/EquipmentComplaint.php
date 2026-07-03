<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentComplaint extends Model
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

    public function equipment()
    {
        // Fallback relationship to prevent eager loading errors.
        return $this->belongsTo(Equipment::class, 'assets_id', 'id');
    }

    public function asset()
    {
        return $this->belongsTo(EquipmentComponent::class, 'assets_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id', 'id');
    }
}
