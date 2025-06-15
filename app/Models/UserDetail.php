<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = [
        'user_id',
        'government_unit_id',
        'full_name',
        'email',
        'phone_number',
    ];
    public function governmentUnit()
    {
        return $this->belongsTo(GovernmentUnit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
