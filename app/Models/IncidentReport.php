<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{
    protected $fillable = [
        'ticket',
        'channel_id',
        'category',
        'category_id',
        'status',
        'call_type',
        'caller_id',
        'phone',
        'phone_unmask',
        'voip_number',
        'caller',
        'created_by',
        'address',
        'location',
        'district_id',
        'district',
        'subdistrict_id',
        'subdistrict',
        'notes',
        'description',
        'incident_created_at',
        'incident_updated_at',
        'created_at',
        'updated_at',
    ];

    public function agencyResponses()
    {
        return $this->hasMany(AgencyResponse::class, 'ticket', 'ticket');
    }

    public function logs()
    {
        return $this->hasMany(IncidentLog::class, 'ticket', 'ticket');
    }

    
}
