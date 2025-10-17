<?php
namespace App\Http\Controllers\companyAdmin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function dailyreport()
    {
        $userId = auth()->user()->id;
        $report = Report::where('company_id', $userId)
            ->select(
                'vehicle_type',
                DB::raw('COUNT(*) as qty'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('vehicle_type')
            ->get();

        return view('company-admin.Reports.dailyreport', compact('report'));
    }

    public function vehicleReport()
    {
        $userId = auth()->user()->id;

        $report = Report::where('company_id', $userId)->get();

        return view('company-admin.Reports.vehiclereport', compact('report'));
    }

}
