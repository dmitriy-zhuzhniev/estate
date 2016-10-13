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
 * @property $region_id
 * @property $city_id
 * @property $street_id
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
        'region_id',
        'district_id',
        'city_id',
        'street_id',
        'house_number',
        'apartment_number',
        'square',
        'floor',
        'max_floor',
        'rooms',
        'user_id',
    ];

    protected static $types = [
        'apartment' => 'Apartment',
        'house' => 'House',
        'parcel' => 'Parcel',
        'garage' => 'Garage',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

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

    public function receivedApartments()
    {
        return $this->belongsToMany(ReceivedApartment::class);
    }

    public function manager()
    {
        return $this->belongsTo('App\User');
    }

    public static function register(
        $title, $type, $realty_id, $customer, $owner, $agreement_id, $realty_goal, $region_id, $city_id,
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
        $obj->region_id = $region_id;
        $obj->city_id = $city_id;
        $obj->house_number = $house_number;
        $obj->apartment_number = $apartment_number;
        $obj->square = $square;
        $obj->floor = $floor;
        $obj->total_floor = $total_floor;
        $obj->rooms = $rooms;
        $obj->user_id = $user_id;

        return $obj;
    }

    public static function getTypes()
    {
        return self::$types;
    }
}
