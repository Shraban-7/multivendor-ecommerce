<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getDistricts($divisionId)
    {
        $districts = District::where('division_id', $divisionId)
            ->select('id', 'name')
            ->get();

        return response()->json($districts);
    }
}
