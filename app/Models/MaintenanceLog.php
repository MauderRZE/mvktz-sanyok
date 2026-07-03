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

    public function equipment()
    {
        // Table repairs links to assets (EquipmentComponent), not equipment directly.
        // We use a dummy self-referential relationship to avoid SQL exceptions during eager loading.
        return $this->belongsTo(Equipment::class, 'assets_id', 'id');
    }

    public function asset()
    {
        return $this->belongsTo(EquipmentComponent::class, 'assets_id');
    }

    public function maintenanceType()
    {
        // Table maintenance_types does not exist.
        // We use a dummy self-referential relationship to avoid SQL exceptions during eager loading.
        return $this->belongsTo(MaintenanceType::class, 'id', 'id');
    }
}
