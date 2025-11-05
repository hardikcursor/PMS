<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userId = auth()->user()->id;

        $vehiclesByType = Vehicle::select('id', 'vehicle_type')->get()->groupBy('vehicle_type');

        $todayStart = now()->startOfDay();
        $todayEnd   = now()->endOfDay();

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
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->sum('amount');
        } else {
            $cycleIds = [];
        }

        $bikeIds     = isset($vehiclesByType['BIKE']) ? $vehiclesByType['BIKE']->pluck('id')->toArray() : [];
        $scooterIds  = isset($vehiclesByType['SCOOTER']) ? $vehiclesByType['SCOOTER']->pluck('id')->toArray() : [];
        $bikeScooter = array_merge($bikeIds, $scooterIds);

        if (!empty($bikeScooter)) {
            $totals['Bike'] = DB::table('reports')
                ->where('company_id', $userId)
                ->whereIn('vehicle_id', $bikeScooter)
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->sum('amount');
        }

        if (isset($vehiclesByType['Four Wheeler']) || isset($vehiclesByType['CAR'])) {
            $fourIds = collect()
                ->merge($vehiclesByType['Four Wheeler'] ?? collect())
                ->merge($vehiclesByType['CAR'] ?? collect())
                ->pluck('id')
                ->toArray();

            $totals['Four Wheeler'] = DB::table('reports')
                ->where('company_id', $userId)
                ->whereIn('vehicle_id', $fourIds)
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->sum('amount');
        } else {
            $fourIds = [];
        }

        $allKnownIds = array_merge($cycleIds, $bikeScooter, $fourIds);

        $totals['Comm Vehicle'] = DB::table('reports')
            ->where('company_id', $userId)
            ->whereNotIn('vehicle_id', $allKnownIds)
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('amount');

        $todayTotal = DB::table('reports')
            ->where('company_id', $userId)
            ->whereBetween('created_at', [$todayStart, $todayEnd])
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

        $sevenDaysAgo = now()->subDays(6)->startOfDay();
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

        $startDate = Carbon::now()->subDays(5)->startOfDay();
        $endDate   = Carbon::now()->endOfDay();

        $collectionsByDay = DB::table('reports')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->where('company_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'), 'desc')
            ->get();

        $labels  = [];
        $amounts = [];

        $period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate->copy()->addDay());
        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $labels[]      = $date->format('d M');
            $amounts[]     = (float) ($collectionsByDay->firstWhere('date', $formattedDate)->total_amount ?? 0);
        }

        return view('User.dashboard', compact(
            'totals',
            'todayTotal',
            'todayCollections',
            'last7DaysCollections',
            'labels',
            'amounts'
        ));
    }

    public function getTotalRevenue()
    {
        $today = now()->toDateString();
        $total = \App\Models\Report::whereDate('created_at', $today)
            ->sum('amount');

        return response()->json([
            'total_revenue' => $total,
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
