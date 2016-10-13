<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function streets()
    {
        return $this->hasMany(Street::class);
    }

    public function apartments()
    {
        return $this->hasMany(Apartment::class);
    }

    public function receivedApartments()
    {
        return $this->hasMany(ReceivedApartment::class);
    }
}
