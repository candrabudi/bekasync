<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    public function governmentUnit()
    {
        return $this->belongsTo(GovernmentUnit::class);
    }

}
