<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    // public function index()
    // {

    //     return view('User.dashboard');
    // }

    public function index()
    {
        $userId = auth()->user()->id;

        $vehiclesByType = Vehicle::select('id', 'vehicle_type')->get()->groupBy('vehicle_type');

        $totals = [
            'Cycle'          => 0,
            'BikeAndScooter' => 0,
            'FourWheeler'    => 0,
            'CVehicle'       => 0,
        ];

        // === Cycle Total ===
        if (isset($vehiclesByType['CYCLE'])) {
            $cycleIds        = $vehiclesByType['CYCLE']->pluck('id')->toArray();
            $totals['Cycle'] = DB::table('reports')
                ->where('company_id', $userId)
                ->whereIn('vehicle_id', $cycleIds)
                ->sum('amount');
        } else {
            $cycleIds = [];
        }

        // === Bike + Scooter Total ===
        $bikeIds = isset($vehiclesByType['BIKE'])
            ? $vehiclesByType['BIKE']->pluck('id')->toArray()
            : [];
        $scooterIds = isset($vehiclesByType['SCOOTER'])
            ? $vehiclesByType['SCOOTER']->pluck('id')->toArray()
            : [];

        $bikeAndScooterIds = array_merge($bikeIds, $scooterIds);

        if (! empty($bikeAndScooterIds)) {
            $totals['BikeAndScooter'] = DB::table('reports')
                ->where('company_id', $userId)
                ->whereIn('vehicle_id', $bikeAndScooterIds)
                ->sum('amount');
        }

        // === Four Wheeler ===
        if (isset($vehiclesByType['CAR'])) {
            $fourIds               = $vehiclesByType['CAR']->pluck('id')->toArray();
            $totals['FourWheeler'] = DB::table('reports')
                ->where('company_id', $userId)
                ->whereIn('vehicle_id', $fourIds)
                ->sum('amount');
        } else {
            $fourIds = [];
        }

        // === Other Vehicles (C-Vehicle) ===
        $allKnownIds        = array_merge($cycleIds, $bikeAndScooterIds, $fourIds);
        $totals['CVehicle'] = DB::table('reports')
            ->where('company_id', $userId)
            ->whereNotIn('vehicle_id', $allKnownIds)
            ->sum('amount');

        return view('User.dashboard', compact('totals'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
