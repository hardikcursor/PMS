<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {

        $company = User::with('license', 'setsubscriptionprice')
            ->role('Company-admin')
            ->get();

        $start       = Carbon::now()->startOfMonth()->subMonths(11);
        $months      = [];
        $totalCounts = [];
        for ($i = 0; $i < 12; $i++) {
            $dt          = $start->copy()->addMonths($i);
            $months[]    = $dt->format('M Y');
            $monthKeys[] = $dt->format('Y-m');
        }

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

        $enabledCounts  = [];
        $disabledCounts = [];
        foreach ($monthKeys as $key) {
            $enabledCounts[]  = $enabledRows->get($key, 0);
            $disabledCounts[] = $disabledRows->get($key, 0);

        }

        return view('super-admin.dashboard', compact('company', 'months', 'enabledCounts', 'disabledCounts'));
    }


       public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

}
