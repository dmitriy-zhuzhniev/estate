<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceivedApartment extends Model
{
    protected $fillable = [
        'advert_id',
        'site',
        'link',
        'city_id',
        'district_id',
        'street_id',
        'title',
        'date',
        'type',
        'rooms',
        'total_square',
        'living_square',
        'kitchen_square',
        'floor',
        'total_floor',
        'price',
        'description',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function street()
    {
        return $this->belongsTo(Street::class);
    }

    public function apartments()
    {
        return $this->belongsToMany(Apartment::class);
    }
}
