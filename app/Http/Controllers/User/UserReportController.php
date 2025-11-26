<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PosUser;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserReportController extends Controller
{
    public function userReport(Request $request)
    {
        $loginUserId = auth()->user()->id;
        $posUsers    = PosUser::where('company_id', $loginUserId)->get();
        $report      = collect();

        if ($request->hasAny(['from_date', 'to_date', 'pos_user_id'])) {
            if (! $request->filled('pos_user_id')) {
                return redirect()->route('user.user.report')
                    ->with('msg', 'Please select user first.');
            }

            $query = Report::join('pos_users', 'reports.pos_user_id', '=', 'pos_users.id')
                ->where('reports.company_id', $loginUserId)
                ->where('reports.pos_user_id', $request->pos_user_id);

            if ($request->filled('from_date') && $request->filled('to_date')) {
                $query->whereBetween(DB::raw('DATE(reports.created_at)'), [$request->from_date, $request->to_date]);
            } elseif ($request->filled('from_date')) {
                $query->whereDate('reports.created_at', '=', $request->from_date);
            } elseif ($request->filled('to_date')) {
                $query->whereDate('reports.created_at', '=', $request->to_date);
            }

            $report = $query
                ->join('vehicles', 'reports.vehicle_id', '=', 'vehicles.id')
                ->selectRaw('
                    vehicles.vehicle_type,
                    COUNT(reports.id) as vehicle_count,
                    SUM(reports.amount) as total_amount
                ')
                ->groupBy('vehicles.vehicle_type')
                ->orderBy('vehicles.vehicle_type')
                ->get();

        }

        if ($request->download == 'pdf') {
            $pdf = Pdf::loadView('User.userreport_pdf', compact('report'));
            return $pdf->download('UserReport.pdf');
        }

        return view('User.userreport', compact('report', 'posUsers'));
    }

}
