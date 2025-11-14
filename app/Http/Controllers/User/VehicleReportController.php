<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;

class VehicleReportController extends Controller
{
    public function vehicleReport()
    {
        $userId = auth()->user()->id;

        $reports = Report::where('reports.company_id', $userId) // <-- reports.company_id સ્પષ્ટ કરો
            ->join('vehicles', 'reports.vehicle_id', '=', 'vehicles.id')
            ->select(
                'reports.*',
                'vehicles.vehicle_type' // તમારી જરૂરિયાત પ્રમાણે 'vehicle_type' કે 'vehicle_name' મુકશો
            )
            ->orderBy('reports.created_at', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at ? $item->created_at->format('d M Y') : 'Unknown';
            });

        return view('User.vehiclereport', compact('reports'));
    }

}
