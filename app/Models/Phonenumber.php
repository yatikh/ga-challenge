<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phonenumber extends Model
{
    public function calls()
    {
        return $this->hasMany('App\Models\Call');
    }
}
