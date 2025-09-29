<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PosMachine;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // public function index()
    // {

    //     $company = User::with('license', 'setsubscriptionprice')
    //         ->role('Company-admin')
    //         ->get();

    //     $start       = Carbon::now()->startOfMonth()->subMonths(11);
    //     $months      = [];
    //     $totalCounts = [];
    //     for ($i = 0; $i < 12; $i++) {
    //         $dt          = $start->copy()->addMonths($i);
    //         $months[]    = $dt->format('M Y');
    //         $monthKeys[] = $dt->format('Y-m');
    //     }

    //     $enabledRows = User::role('Company-admin')
    //         ->where('status', 1)
    //         ->where('created_at', '>=', $start)
    //         ->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month, COUNT(*) AS total')
    //         ->groupBy('year', 'month')
    //         ->get()
    //         ->mapWithKeys(function ($row) {
    //             $key = $row->year . '-' . str_pad($row->month, 2, '0', STR_PAD_LEFT);
    //             return [$key => (int) $row->total];
    //         });

    //     $disabledRows = User::role('Company-admin')
    //         ->where('status', 0)
    //         ->where('created_at', '>=', $start)
    //         ->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month, COUNT(*) AS total')
    //         ->groupBy('year', 'month')
    //         ->get()
    //         ->mapWithKeys(function ($row) {
    //             $key = $row->year . '-' . str_pad($row->month, 2, '0', STR_PAD_LEFT);
    //             return [$key => (int) $row->total];
    //         });

    //     $enabledCounts  = [];
    //     $disabledCounts = [];
    //     foreach ($monthKeys as $key) {
    //         $enabledCounts[]  = $enabledRows->get($key, 0);
    //         $disabledCounts[] = $disabledRows->get($key, 0);

    //     }

    //     $license = Subscription::with(['subcreatedcompany.devices'])->get();

    //     return view('super-admin.dashboard', compact('company', 'months', 'enabledCounts', 'disabledCounts', 'license'));
    // }

    public function index()
    {
        // Fetch all companies with license and subscription price
        $company = User::with('license', 'setsubscriptionprice')
            ->role('Company-admin')
            ->get();

        // Last 12 months
        $start     = Carbon::now()->startOfMonth()->subMonths(11);
        $months    = [];
        $monthKeys = [];
        for ($i = 0; $i < 12; $i++) {
            $dt          = $start->copy()->addMonths($i);
            $months[]    = $dt->format('M Y');
            $monthKeys[] = $dt->format('Y-m');
        }

        // Enabled Companies counts per month
        $enabledRows = User::role('Company-admin')
            ->where('status', 1)
            ->where('created_at', '>=', $start)
            ->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month, COUNT(*) AS total')
            ->groupBy('year', 'month')
            ->get()
            ->mapWithKeys(function ($row) {
                $key = $row->year . '-' . str_pad($row->month, 2, '0', STR_PAD_LEFT);
                return [$key => (int) $row->total];
            });

        // Disabled Companies counts per month
        $disabledRows = User::role('Company-admin')
            ->where('status', 0)
            ->where('created_at', '>=', $start)
            ->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month, COUNT(*) AS total')
            ->groupBy('year', 'month')
            ->get()
            ->mapWithKeys(function ($row) {
                $key = $row->year . '-' . str_pad($row->month, 2, '0', STR_PAD_LEFT);
                return [$key => (int) $row->total];
            });

        // Build arrays for chart
        $enabledCounts  = [];
        $disabledCounts = [];
        foreach ($monthKeys as $key) {
            $enabledCounts[]  = $enabledRows->get($key, 0);
            $disabledCounts[] = $disabledRows->get($key, 0);
        }

        // POS Machines counts per month
        $posMachineCounts = [];
        foreach ($monthKeys as $key) {
            [$year, $month] = explode('-', $key);
            $count          = PosMachine::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
            $posMachineCounts[] = $count;
        }

        // Fetch all subscriptions with related company devices
        $license = Subscription::with(['subcreatedcompany.devices'])->get();

        return view('super-admin.dashboard', compact(
            'company',
            'months',
            'enabledCounts',
            'disabledCounts',
            'posMachineCounts',
            'license'
        ));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

}
