<?php

namespace App\Http\Controllers;

use App\Domain\Shipping\Models\District;

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
