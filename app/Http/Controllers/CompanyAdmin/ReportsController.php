<?php

namespace App\Http\Controllers\companyAdmin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function dailyreport()
{
    $report = Report::select(
            'vehicle_type',
            'serial_number',
            DB::raw('COUNT(pos_user_id) as pos_user_count'),
            DB::raw('SUM(amount) as amount')
            
        )
        ->groupBy('vehicle_type','serial_number')
        ->get();

    return view('company-admin.Reports.dailyreport', compact('report'));
}


    public function vehicleReport()
    {
        $report = Report::get();
        return view('company-admin.Reports.vehiclereport',compact('report'));
    }
}
