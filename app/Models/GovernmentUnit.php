<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GovernmentUnit extends Model
{
    protected $fillable = [
        'name',
        'long_name'
    ];
    public function userDetails()
    {
        return $this->hasMany(UserDetail::class);
    }
}
