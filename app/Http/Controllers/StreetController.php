<?php

namespace App\Http\Controllers;

use App\Street;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StreetController extends Controller
{
    public function jsonAll(Request $request)
    {
        return new Response(Street::where('city_id', '=', $request->city_id)->get());
    }
}
