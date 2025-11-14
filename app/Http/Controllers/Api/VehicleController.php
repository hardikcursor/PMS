<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_type' => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
        ]);

        $vehicle               = new Vehicle();
        $vehicle->vehicle_type = $request->vehicle_type;
        $vehicle->price        = $request->price;
        $vehicle->save();

        return response()->json($vehicle, 201);
    }

   public function getVehiclesByUser(Request $request)
{
  
    $request->validate([
        'username' => 'required|string',
    ]);
    $user = User::where('username', $request->username)->first();

    if (! $user) {
        return response()->json([
            'status'  => false,
            'message' => 'User not found',
        ], 404);
    }
    $vehicles = Vehicle::where('company_id', $user->id)
        ->select('id', 'vehicle_type')
        ->orderBy('vehicle_type')
        ->get();

    if ($vehicles->isEmpty()) {
        return response()->json([
            'status'  => false,
            'message' => 'No vehicle data found for this user',
        ], 404);
    }
    return response()->json([
        'status' => true,
        'data'   => [
            'username' => $user->username,
            'vehicles' => $vehicles->map(function ($vehicle) {
                return [
                    'id'           => $vehicle->id,
                    'vehicle_type' => strtoupper($vehicle->vehicle_type),
                ];
            }),
        ],
    ]);
}


}
