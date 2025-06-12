<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GovernmentUnit extends Model
{
    public function userDetails()
    {
        return $this->hasMany(UserDetail::class);
    }

}
