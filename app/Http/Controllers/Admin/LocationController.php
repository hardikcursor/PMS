<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
     public function index()
    {
        $locations = Location::all();
        return view('super-admin.locations.index', compact('locations'));
    }

    public function showOSM(Location $location)
    {
        return view('super-admin.locations.showOSM', compact('location'));
    }
}
