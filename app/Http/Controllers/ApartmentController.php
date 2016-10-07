<?php

namespace App\Http\Controllers;

use App\Apartment;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Http\Requests;

class ApartmentController extends Controller
{
    public function index()
    {
        $user = Sentinel::getUser();

        $apartments = Apartment::whereUserId($user->id)->get();

        if (count($apartments) == 0) {
            return redirect('create');
        }

        return View::make('apartments.index',[
                'apartments' => $apartments,
            ]
        );
    }

    public function create()
    {
        return View::make('apartments.create');
    }

    public function store(Request $request)
    {
        $user = Sentinel::getUser();

        $this->validate($request, [
            'title' => 'required',
            'type' => 'required',
            'realty_id' => 'required',
            'customer' => 'required',
            'owner' => 'required',
            'agreement_id' => 'required',
            'realty_goal' => 'required',
            'region' => 'required',
            'city' => 'required',
            'house_number' => 'required',
            'apartment_number' => 'required',
            'square' => 'required',
            'floor' => 'required',
            'total_floor' => 'required',
            'rooms' => 'required',
        ]);

        $apartment = Apartment::register(
            trim($request->title),
            $request->type,
            trim($request->realty_id),
            trim($request->customer),
            trim($request->owner),
            trim($request->agreement_id),
            trim($request->realty_goal),
            $request->region,
            $request->city,
            trim($request->house_number),
            trim($request->apartment_number),
            trim($request->square),
            trim($request->floor),
            trim($request->total_floor),
            trim($request->rooms),
            $user->id
        );

        $apartment->save();

        return redirect('/');
    }

    public function parse($id)
    {
        $apartment = Apartment::find($id);

        $this->parseOLX($apartment);
    }

    private function parseOlx(Apartment $apartment)
    {
        $url = $this->getOlxUrl($apartment);
        dd($url);
    }

    /**
     * [base_url]/[type_url]/[region/city]?/q-[search_query]?/[options]
     */
    private function getOlxUrl(Apartment $apartment)
    {
        $base_url = 'https://www.olx.ua/nedvizhimost/';
        $type_url = [
            'apartment' => 'prodazha-kvartir',
            'house' => 'prodazha-domov',
            'parcel' => 'prodazha-zemli',
            'garage' => 'prodazha-garazhey-stoyanok',
        ];
        $filters = [
            'rooms_from' => 'search[filter_float_number_of_rooms%3Afrom]',
            'rooms_to' => 'search[filter_float_number_of_rooms%3Ato]',
            'square_from' => 'search[filter_float_total_living_area%3Afrom]',
            'square_to' => 'search[filter_float_total_living_area%3Ato]',
            'floor_from' => 'search[filter_float_floor%3Afrom]',
            'floor_to' => 'search[filter_float_floor%3Ato]',
        ];

        $options = [];

        if (!empty($apartment->rooms)) {
            $options[] = $filters['rooms_from'] . '=' . $apartment->rooms;
            $options[] = $filters['rooms_to'] . '=' . $apartment->rooms;
        }
        if (!empty($apartment->floor)) {
            $options[] = $filters['floor_from'] . '=' . $apartment->floor;
            $options[] = $filters['floor_to'] . '=' . $apartment->floor;
        }
        if (!empty($apartment->square)) {
            $options[] = $filters['square_from'] . '=' . $apartment->square;
            $options[] = $filters['square_to'] . '=' . $apartment->square;
        }

        $url = $base_url . $type_url['apartment'] . '/?' . implode('&', $options);

        return $url;
    }
}
