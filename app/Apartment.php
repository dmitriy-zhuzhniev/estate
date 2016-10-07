<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Apartment
 *
 * @property string $title
 * @property $type
 * @property $realty_id
 * @property $customer
 * @property $owner
 * @property $agreement_id
 * @property $realty_goal
 * @property $region
 * @property $city
 * @property $house_number
 * @property $apartment_number
 * @property $square
 * @property $floor
 * @property $total_floor
 * @property $rooms
 * @property User $user
 * @property int $user_id
 */
class Apartment extends Model
{

    protected $fillable = [
        'title',
        'type',
        'realty_id',
        'customer',
        'owner',
        'agreement_id',
        'realty_goal',
        'region',
        'city',
        'house_number',
        'apartment_number',
        'square',
        'floor',
        'max_floor',
        'rooms',
        'user_id',
    ];

    public function manager()
    {
        return $this->belongsTo('App\User');
    }

    public static function register(
        $title, $type, $realty_id, $customer, $owner, $agreement_id, $realty_goal, $region, $city,
        $house_number, $apartment_number, $square, $floor, $total_floor, $rooms, $user_id
    ) {
        $obj = new self();
        $obj->title = $title;
        $obj->type = $type;
        $obj->realty_id = $realty_id;
        $obj->realty_goal = $realty_goal;
        $obj->customer = $customer;
        $obj->owner = $owner;
        $obj->agreement_id = $agreement_id;
        $obj->region = $region;
        $obj->city = $city;
        $obj->house_number = $house_number;
        $obj->apartment_number = $apartment_number;
        $obj->square = $square;
        $obj->floor = $floor;
        $obj->total_floor = $total_floor;
        $obj->rooms = $rooms;
        $obj->user_id = $user_id;

        return $obj;
    }
}
