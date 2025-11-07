<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->user()->id;

        $allVehicleTypes = Report::where('company_id', $userId)
            ->select('vehicle_type')
            ->groupBy('vehicle_type')
            ->orderBy('vehicle_type', 'asc')
            ->pluck('vehicle_type');

        $today    = now()->toDateString();
        $fromDate = $request->from_date ?: ($request->to_date ?: $today);
        $toDate   = $request->to_date ?: ($request->from_date ?: $today);

        $filteredData = Report::where('company_id', $userId)
            ->select(
                'vehicle_type',
                DB::raw('COUNT(*) as qty'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->whereBetween(DB::raw('DATE(created_at)'), [$fromDate, $toDate])
            ->groupBy('vehicle_type')
            ->orderBy('vehicle_type', 'asc')
            ->get()
            ->keyBy('vehicle_type');

        $reports = collect();
        foreach ($allVehicleTypes as $type) {
            $reports->push([
                'vehicle_type' => $type,
                'qty'          => $filteredData[$type]->qty ?? 0,
                'total_amount' => $filteredData[$type]->total_amount ?? 0,
            ]);
        }

        if ($request->has('download') && $request->download === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('User.report_pdf', [
                'reports'   => $reports,
                'from_date' => $fromDate,
                'to_date'   => $toDate,
            ])->setPaper('a4', 'portrait');

            return $pdf->download('report_' . now()->format('d_m_Y') . '.pdf');
        }

        return view('User.report', compact('reports'))
            ->with([
                'from_date' => $fromDate,
                'to_date'   => $toDate,
            ]);
    }

}
