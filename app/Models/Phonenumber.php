<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Eloquence\Behaviours\CamelCasing;

class Phonenumber extends Model
{
    use CamelCasing;

    public function calls()
    {
        return $this->hasMany('App\Models\Call');
    }
}
