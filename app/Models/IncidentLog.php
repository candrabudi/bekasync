<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentLog extends Model
{
    protected $fillable = [
        'incident_report_id',
        'ticket',
        'status',
        'created_by',
        'created_by_name',
        'updated_by',
        'updated_by_name',
        'created_at',
        'updated_at',
    ];

    public function incident()
    {
        return $this->belongsTo(IncidentReport::class, 'ticket', 'ticket');
    }
}
