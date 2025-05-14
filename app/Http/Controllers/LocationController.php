<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getStatesByCountry($country_id)
    {
        $states = State::where('country_id', $country_id)->get(['id', 'name']);
        return response()->json($states);
    }
}
