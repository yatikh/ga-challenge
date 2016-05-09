<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    public function phonenumber()
    {
        return $this->belongsTo('App\Models\Phonenumber');
    }
}
