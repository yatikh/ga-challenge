<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Eloquence\Behaviours\CamelCasing;

class Call extends Model
{
    use CamelCasing;

    public function phonenumber()
    {
        return $this->belongsTo('App\Models\Phonenumber');
    }
}
