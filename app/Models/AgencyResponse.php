<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgencyResponse extends Model
{
    protected $fillable = [
        'incident_report_id',
        'report_id',
        'ticket',
        'dinas_id',
        'dinas',
        'status',
        'created_at',
        'updated_at',
    ];

    public function incident()
    {
        return $this->belongsTo(IncidentReport::class, 'ticket', 'ticket');
    }
}
