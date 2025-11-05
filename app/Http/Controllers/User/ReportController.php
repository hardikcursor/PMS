<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
{
    $userId = auth()->user()->id;

    $query = Report::where('company_id', $userId)
        ->select(
            'vehicle_type',
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as qty'),
            DB::raw('SUM(amount) as total_amount')
        )
        ->groupBy('vehicle_type', DB::raw('DATE(created_at)'))
        ->orderBy(DB::raw('DATE(created_at)'), 'desc');

    // ✅ PDF Download
    if ($request->has('download') && $request->download === 'pdf') {
        if ($request->filled('vehicle_type')) {
            $reports = Report::where('company_id', $userId)
                ->where('vehicle_type', $request->vehicle_type)
                ->select(
                    'vehicle_type',
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as qty'),
                    DB::raw('SUM(amount) as total_amount')
                )
                ->groupBy('vehicle_type', DB::raw('DATE(created_at)'))
                ->orderBy(DB::raw('DATE(created_at)'), 'desc')
                ->get();
        } else {
            $reports = $query->get();
        }

        $pdf = Pdf::loadView('User.report_pdf', [
            'reports'     => $reports,
            'vehicleType' => $request->vehicle_type ?? 'All Types',
        ])->setPaper('a4', 'portrait');

        return $pdf->download('report_' . ($request->vehicle_type ?? 'all') . '_' . now()->format('d_m_Y') . '.pdf');
    }

    $reports = $query->get();
    return view('User.report', compact('reports'));
}


}
