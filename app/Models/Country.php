<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * Phonenumbers registered in this country.
     */
    public function phonenumbers()
    {
        return $this->hasMany('App\Models\Phonenumber');
    }
}
