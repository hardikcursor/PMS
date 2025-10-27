<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Report;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function dashboard()
    {
        $userId = auth()->user()->id;


        $vehiclesByType = Vehicle::select('id', 'vehicle_type')->get()->groupBy('vehicle_type');
        $collections = Report::get();

        $totals = [
            'Cycle'          => 0,
            'BikeAndScooter' => 0,
            'FourWheeler'    => 0,
            'CVehicle'       => 0,
        ];

        if (isset($vehiclesByType['CYCLE'])) {
            $cycleIds        = $vehiclesByType['CYCLE']->pluck('id')->toArray();
            $totals['Cycle'] = DB::table('reports')
                ->where('company_id', $userId)
                ->whereIn('vehicle_id', $cycleIds)
                ->sum('amount');
        } else {
            $cycleIds = [];
        }
        $bikeIds = isset($vehiclesByType['BIKE'])
            ? $vehiclesByType['BIKE']->pluck('id')->toArray()
            : [];
        $scooterIds = isset($vehiclesByType['SCOOTER'])
            ? $vehiclesByType['SCOOTER']->pluck('id')->toArray()
            : [];

        $bikeAndScooterIds = array_merge($bikeIds, $scooterIds);

        if (!empty($bikeAndScooterIds)) {
            $totals['BikeAndScooter'] = DB::table('reports')
                ->where('company_id', $userId)
                ->whereIn('vehicle_id', $bikeAndScooterIds)
                ->sum('amount');
        }
        if (isset($vehiclesByType['FOURWHEEL']) || isset($vehiclesByType['CAR'])) {
            $fourIds = collect()
                ->merge($vehiclesByType['FOURWHEEL'] ?? collect())
                ->merge($vehiclesByType['CAR'] ?? collect())
                ->pluck('id')
                ->toArray();

            $totals['FourWheeler'] = DB::table('reports')
                ->where('company_id', $userId)
                ->whereIn('vehicle_id', $fourIds)
                ->sum('amount');
        } else {
            $fourIds = [];
        }
        $allKnownIds = array_merge($cycleIds, $bikeAndScooterIds, $fourIds);
        $totals['CVehicle'] = DB::table('reports')
            ->where('company_id', $userId)
            ->whereNotIn('vehicle_id', $allKnownIds)
            ->sum('amount');

        $fromDate = now()->subDays(2)->startOfDay();
        $toDate   = now()->endOfDay();

        $todayCollections = DB::table('reports')
            ->select(
                'serial_number',
                'name',
                DB::raw('SUM(amount) as total_collection'),
                DB::raw('COUNT(vehicle_no) as total_vehicle'),
                DB::raw('DATE(created_at) as date')
            )
            ->where('company_id', $userId)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->groupBy('serial_number', 'name', DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'), 'desc')
            ->get();

        $sevenDaysAgo = now()->subDays(6)->startOfDay(); // last 7 days including today

        $last7DaysCollections = DB::table('reports')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(vehicle_no) as total_vehicle')
            )
            ->where('company_id', $userId)
            ->where('created_at', '>=', $sevenDaysAgo)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'), 'asc')
            ->pluck('total_vehicle', 'date'); 

        return view('User.dashboard', compact('totals', 'collections', 'todayCollections','last7DaysCollections'));
    }


    public function getTotalRevenue()
    {
        $total = DB::table('reports')->sum('amount');

        return response()->json([
            'status' => true,
            'total_revenue' => $total,
        ]);
    }

    public function todayUserCollections() {}



    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
