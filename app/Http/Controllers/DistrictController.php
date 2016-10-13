<?php

namespace App\Http\Controllers;

use App\District;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DistrictController extends Controller
{
    public function jsonAll(Request $request)
    {
        return new Response(District::where('city_id', '=', $request->city_id)->get());
    }
}
