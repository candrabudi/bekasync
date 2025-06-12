<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CdrReport extends Model
{
    protected $fillable = [
        'ticket',
        'phone',
        'phone_unmask',
        'voip_number',
        'datetime_entry_queue',
        'duration_wait',
        'datetime_init',
        'datetime_end',
        'duration',
        'status',
        'uniqueid',
        'extension',
        'agent_name',
        'recording_file',
    ];
}
