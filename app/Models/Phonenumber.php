<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Country;

class Phonenumber extends Model
{
    /**
     * Country in which phonenumber was bought.
     */
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function calls()
    {
        return $this->hasMany('App\Models\Call');
    }
}
