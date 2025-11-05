<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class VehicleReportController extends Controller
{
public function vehicleReport()
{
    $userId = auth()->user()->id;

   
    $reports = Report::where('company_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy(function ($item) {
            return $item->created_at ? $item->created_at->format('d M Y') : 'Unknown';
        });

    return view('User.vehiclereport', compact('reports'));
}



}
