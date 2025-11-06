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
       
        $company = User::with('license', 'setsubscriptionprice')
            ->role(['Company-admin','User'])
            ->get();

        
        $start     = Carbon::now()->startOfMonth()->subMonths(11);
        $months    = [];
        $monthKeys = [];

        for ($i = 0; $i < 12; $i++) {
            $dt          = $start->copy()->addMonths($i);
            $months[]    = $dt->format('M Y');
            $monthKeys[] = $dt->format('Y-m');
        }


        $enabledCompanyRows = User::role(['Company-admin','User'])
            ->where('status', 1)
            ->where('created_at', '>=', $start)
            ->get()
            ->groupBy(fn($item) => $item->created_at->format('Y-m'));

        $enabledCompanyCounts = [];
        $enabledCompanyNames  = [];
        foreach ($monthKeys as $key) {
            $enabledCompanyCounts[] = isset($enabledCompanyRows[$key]) ? $enabledCompanyRows[$key]->count() : 0;
            $enabledCompanyNames[]  = isset($enabledCompanyRows[$key]) ? $enabledCompanyRows[$key]->pluck('name')->toArray() : [];
        }

       
        $disabledCompanyRows = User::role('Company-admin')
            ->where('status', 0)
            ->where('created_at', '>=', $start)
            ->get()
            ->groupBy(fn($item) => $item->created_at->format('Y-m'));

        $disabledCompanyCounts = [];
        $disabledCompanyNames  = [];
        foreach ($monthKeys as $key) {
            $disabledCompanyCounts[] = isset($disabledCompanyRows[$key]) ? $disabledCompanyRows[$key]->count() : 0;
            $disabledCompanyNames[]  = isset($disabledCompanyRows[$key]) ? $disabledCompanyRows[$key]->pluck('name')->toArray() : [];
        }

    
        $enabledUserRows = User::role('User')
            ->where('status', 1)
            ->where('created_at', '>=', $start)
            ->get()
            ->groupBy(fn($item) => $item->created_at->format('Y-m'));

        $enabledUserCounts = [];
        $enabledUserNames  = [];
        foreach ($monthKeys as $key) {
            $enabledUserCounts[] = isset($enabledUserRows[$key]) ? $enabledUserRows[$key]->count() : 0;
            $enabledUserNames[]  = isset($enabledUserRows[$key]) ? $enabledUserRows[$key]->pluck('name')->toArray() : [];
        }

       
        $disabledUserRows = User::role('User')
            ->where('status', 0)
            ->where('created_at', '>=', $start)
            ->get()
            ->groupBy(fn($item) => $item->created_at->format('Y-m'));

        $disabledUserCounts = [];
        $disabledUserNames  = [];
        foreach ($monthKeys as $key) {
            $disabledUserCounts[] = isset($disabledUserRows[$key]) ? $disabledUserRows[$key]->count() : 0;
            $disabledUserNames[]  = isset($disabledUserRows[$key]) ? $disabledUserRows[$key]->pluck('name')->toArray() : [];
        }

     
        $posMachineCounts = [];
        $posNames         = [];
        foreach ($monthKeys as $key) {
            [$year, $month] = explode('-', $key);

            $posMachines = PosMachine::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->get();

            $posMachineCounts[] = $posMachines->count();
            $posNames[]         = $posMachines->pluck('serial_number')->toArray();
        }

   
        $license = Subscription::with(['subcreatedcompany.devices'])->get();

        return view('super-admin.dashboard', compact(
            'company',
            'months',
            'enabledCompanyCounts',
            'disabledCompanyCounts',
            'enabledUserCounts',
            'disabledUserCounts',
            'posMachineCounts',
            'enabledCompanyNames',
            'disabledCompanyNames',
            'enabledUserNames',
            'disabledUserNames',
            'posNames',
            'license'
        ));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

}
