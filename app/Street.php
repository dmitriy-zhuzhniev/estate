<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    protected $fillable = ['city_id', 'name', 'lun_id'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function apartments()
    {
        return $this->belongsToMany(Apartment::class);
    }

    public function receivedApartments()
    {
        return $this->belongsToMany(ReceivedApartment::class);
    }
}
