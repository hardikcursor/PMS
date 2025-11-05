<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fare_metrix;
use Illuminate\Http\Request;
use App\Models\User;

class FaremetrixController extends Controller
{
    public function getFareMatrix(Request $request)
    {

        $request->validate([
            'username' => 'required|string',
        ]);

        $username = $request->input('username');


        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }


        $fare_matrix = Fare_metrix::with(['vehicleCategory', 'slot'])
            ->where('user_id', $user->id)
            ->get();

        if ($fare_matrix->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No fares found for this user'
            ], 404);
        }


        foreach ($fare_matrix as $fare) {
            $category = $fare->vehicleCategory?->vehicle_type ?? 'Unknown';

            if (!$fare->slot || empty($fare->slot->slot_hours)) {
                continue;
            }

            $hours = explode(',', $fare->slot->slot_hours);

            foreach ($hours as $hour) {
                $result['fares_matrix'][$category][] = [
                    'hours' => (int) $hour,
                    'price' => (float) $fare->rate,
                ];
            }
        }

        return response()->json($result);
    }
}
