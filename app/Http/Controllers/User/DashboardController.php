<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use DateInterval;
use DatePeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $userId     = auth()->id();
        $todayStart = now()->startOfDay();
        $todayEnd   = now()->endOfDay();

        $vehiclesByType = Vehicle::where('company_id', $userId)
            ->select('id', 'vehicle_type')
            ->get()
            ->groupBy('vehicle_type');

        $totals = [
            'Cycle'        => 0,
            'Bike'         => 0,
            'Four Wheeler' => 0,
            'Comm Vehicle' => 0,
        ];

        $cycleIds = $vehiclesByType['CYCLE']->pluck('id')->toArray() ?? [];
        if (! empty($cycleIds)) {
            $totals['Cycle'] = DB::table('reports')
                ->where('company_id', $userId)
                ->whereIn('vehicle_id', $cycleIds)
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->sum('amount');
        }

        $bikeScooterIds = collect($vehiclesByType['BIKE'] ?? [])
            ->merge($vehiclesByType['SCOOTER'] ?? [])
            ->pluck('id')
            ->toArray();

        if (! empty($bikeScooterIds)) {
            $totals['Bike'] = DB::table('reports')
                ->where('company_id', $userId)
                ->whereIn('vehicle_id', $bikeScooterIds)
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->sum('amount');
        }

        $fourIds = collect($vehiclesByType['Four Wheeler'] ?? [])
            ->merge($vehiclesByType['CAR'] ?? [])
            ->pluck('id')
            ->toArray();

        if (! empty($fourIds)) {
            $totals['Four Wheeler'] = DB::table('reports')
                ->where('company_id', $userId)
                ->whereIn('vehicle_id', $fourIds)
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->sum('amount');
        }

        $knownIds               = array_merge($cycleIds, $bikeScooterIds, $fourIds);
        $totals['Comm Vehicle'] = DB::table('reports')
            ->where('company_id', $userId)
            ->whereNotIn('vehicle_id', $knownIds)
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

        $sevenDaysAgo         = now()->subDays(6)->startOfDay();
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

        $startDate = now()->subDays(6)->startOfDay();
        $endDate   = now()->endOfDay();

        $collectionsByDay = DB::table('reports')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->where('company_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'), 'asc')
            ->get();

        $labels  = [];
        $amounts = [];

        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate->copy()->addSecond());
        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $labels[]      = $date->format('d M');
            $amounts[]     = (float) ($collectionsByDay->firstWhere('date', $formattedDate)->total_amount ?? 0);
        }
        $monthWiseData = DB::table('reports')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%b %Y") as month'),
                DB::raw('COUNT(vehicle_no) as total_vehicle'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->where('company_id', $userId)
            ->groupBy('month')
            ->orderBy(DB::raw('MIN(created_at)'), 'asc')
            ->get();

        $totalRevenue  = $monthWiseData->sum('total_amount');
        $totalVehicles = $monthWiseData->sum('total_vehicle');

        return view('User.dashboard', [
            'totals'               => $totals,
            'todayTotal'           => $todayTotal,
            'todayCollections'     => $todayCollections,
            'last7DaysCollections' => $last7DaysCollections,
            'labels'               => $labels,
            'amounts'              => $amounts,
            'monthLabels'          => $monthWiseData->pluck('month'),
            'monthVehicles'        => $monthWiseData->pluck('total_vehicle'),
            'monthAmounts'         => $monthWiseData->pluck('total_amount'),
            'totalRevenue'         => $totalRevenue,
            'totalVehicles'        => $totalVehicles,
        ]);
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
