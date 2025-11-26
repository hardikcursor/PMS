<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $userId       = auth()->user()->id;
        $userVehicles = Vehicle::where('company_id', $userId)
            ->orderBy('vehicle_type')
            ->get();

        $today    = now()->toDateString();
        $fromDate = $request->from_date;
        $toDate   = $request->to_date ?: $today;

        $query = Report::join('vehicles', 'reports.vehicle_id', '=', 'vehicles.id')
            ->where('reports.company_id', $userId);
        if ($fromDate && $toDate) {
            $query->whereBetween(DB::raw('DATE(reports.created_at)'), [$fromDate, $toDate]);
        } elseif ($toDate) {
            $query->whereDate('reports.created_at', $toDate);
        } elseif ($fromDate) {
            $query->whereDate('reports.created_at', '>=', $fromDate);
        }

        if ($request->filled('vehicle_id')) {
            $query->where('reports.vehicle_id', $request->vehicle_id);
        }

        $filteredData = $query->select(
            'reports.vehicle_id',
            'vehicles.vehicle_type',
            DB::raw('COUNT(*) as qty'),
            DB::raw('SUM(reports.amount) as total_amount')
        )
            ->groupBy('reports.vehicle_id', 'vehicles.vehicle_type')
            ->orderBy('vehicles.vehicle_type')
            ->get()
            ->keyBy('vehicle_id');

        $reports = collect();
        foreach ($userVehicles as $vehicle) {
            if ($request->filled('vehicle_id') && $vehicle->id != $request->vehicle_id) {
                continue;
            }
            $data = $filteredData->get($vehicle->id);
            $reports->push([
                'vehicle_id'   => $vehicle->id,
                'vehicle_type' => $vehicle->vehicle_type,
                'qty'          => $data->qty ?? 0,
                'total_amount' => $data->total_amount ?? 0,
            ]);
        }

        // PDF Download 
        
        if ($request->has('download') && $request->download === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('User.report_pdf', [
                'reports'           => $reports,
                'userVehicles'      => $userVehicles,
                'from_date'         => $fromDate,
                'to_date'           => $toDate,
                'selectedVehicleId' => $request->vehicle_id ?? null,
            ])->setPaper('a4', 'portrait');

            return $pdf->download('report_' . now()->format('d_m_Y') . '.pdf');
        }

        return view('User.report', [
            'reports'           => $reports,
            'userVehicles'      => $userVehicles,
            'from_date'          => $fromDate,
            'to_date'            => $toDate,
            'selectedVehicleId' => $request->vehicle_id ?? null,
        ]);
    }

}
