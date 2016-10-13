<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CityController extends Controller
{
    public function jsonAll(Request $request)
    {
        return new Response(City::where('region_id', '=', $request->region_id)->get());
    }
}
