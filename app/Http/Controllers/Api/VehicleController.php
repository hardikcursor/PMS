<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $vehicle = new Vehicle();
        $vehicle->vehicle_type = $request->vehicle_type;
        $vehicle->price = $request->price;
        $vehicle->save();

        return response()->json($vehicle, 201);
    }

    public function show()
    {
        $vehicle = Vehicle::get();
        return response()->json($vehicle);
    }
}
