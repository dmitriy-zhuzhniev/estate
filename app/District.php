<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = ['city_id', 'name', 'olx_id', 'lun_id'];

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
