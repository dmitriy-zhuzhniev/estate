<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function apartments()
    {
        return $this->hasMany(Apartment::class);
    }
}
