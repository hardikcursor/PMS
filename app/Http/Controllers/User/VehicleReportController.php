<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;

class VehicleReportController extends Controller
{
    public function vehicleReport()
    {
        $userId = auth()->user()->id;

        $reports = Report::where('reports.company_id', $userId) 
            ->join('vehicles', 'reports.vehicle_id', '=', 'vehicles.id')
            ->select(
                'reports.*',
                'vehicles.vehicle_type' 
            )
            ->orderBy('reports.created_at', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at ? $item->created_at->format('d M Y') : 'Unknown';
            });

        return view('User.vehiclereport', compact('reports'));
    }

}
