<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Nomination;
use App\Models\Opportunity;
use App\Models\Partner;
use App\Models\TrainingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $base = Opportunity::with(['type', 'partner']);

        if ($request->filled('year')) {
            $base->whereYear('start_date', (int) $request->input('year'));
        }

        if ($request->filled('status')) {
            $base->where('status', $request->input('status'));
        }

        if ($request->filled('mode')) {
            $base->where('delivery_mode', $request->input('mode'));
        }

        $opportunities = $base->orderByDesc('id')->paginate(25)->withQueryString();

        $stats = [
            'opportunities_total' => Opportunity::count(),
            'opportunities_open' => Opportunity::where('status', 'open_for_nomination')->count(),
            'nominations_total' => Nomination::count(),
            'employees_total' => Employee::count(),
        ];

        $partnersStats = Partner::withCount('opportunities')
            ->orderByDesc('opportunities_count')
            ->take(5)
            ->get();

        $fundingStats = Opportunity::select('funding_details.funding_type', DB::raw('count(*) as total'))
            ->leftJoin('funding_details', 'funding_details.id', '=', 'opportunities.funding_detail_id')
            ->groupBy('funding_details.funding_type')
            ->orderByDesc('total')
            ->get();

        $fairnessMost = Employee::withCount('nominations')
            ->orderByDesc('nominations_count')
            ->take(5)
            ->get();

        $fairnessLeast = Employee::withCount('nominations')
            ->orderBy('nominations_count')
            ->take(5)
            ->get();

        $departmentsStats = DB::table('departments')
            ->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
            ->leftJoin('nominations', 'nominations.employee_id', '=', 'employees.id')
            ->select('departments.name', DB::raw('count(nominations.id) as total'))
            ->groupBy('departments.id', 'departments.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $missionsStats = DB::table('missions')
            ->leftJoin('employees', 'employees.mission_id', '=', 'missions.id')
            ->leftJoin('nominations', 'nominations.employee_id', '=', 'employees.id')
            ->select('missions.name', DB::raw('count(nominations.id) as total'))
            ->groupBy('missions.id', 'missions.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('reports.index', compact(
            'opportunities',
            'stats',
            'partnersStats',
            'fundingStats',
            'fairnessMost',
            'fairnessLeast',
            'departmentsStats',
            'missionsStats'
        ));
    }

    public function exportCsv(Request $request)
    {
        $query = Opportunity::with('type');

        if ($request->filled('year')) {
            $query->whereYear('start_date', (int) $request->input('year'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('mode')) {
            $query->where('delivery_mode', $request->input('mode'));
        }

        $rows = $query->orderByDesc('id')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="ltcms-opportunities.csv"',
        ];

        $callback = function () use ($rows) {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['Reference', 'Title', 'Type', 'Mode', 'Status', 'Start Date', 'End Date']);
            foreach ($rows as $row) {
                fputcsv($output, [
                    $row->reference_no,
                    $row->title,
                    optional($row->type)->name,
                    $row->delivery_mode,
                    $row->status,
                    $row->start_date,
                    $row->end_date,
                ]);
            }
            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $query = Opportunity::query();

        if ($request->filled('year')) {
            $query->whereYear('start_date', (int) $request->input('year'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('mode')) {
            $query->where('delivery_mode', $request->input('mode'));
        }

        $rows = $query->orderByDesc('id')->get();

        return view('reports.print', compact('rows'));
    }

    public function syncTrainingHistory()
    {
        $nominations = Nomination::whereIn('status', ['completed', 'attended'])
            ->whereNotNull('employee_id')
            ->get();

        foreach ($nominations as $nomination) {
            TrainingHistory::updateOrCreate([
                'employee_id' => $nomination->employee_id,
                'opportunity_id' => $nomination->opportunity_id,
                'nomination_id' => $nomination->id,
            ], [
                'completion_status' => $nomination->status === 'completed' ? 'completed' : 'not_completed',
                'certificate_received' => $nomination->certificate_received,
                'completion_date' => $nomination->nomination_date,
            ]);
        }

        return redirect()->route('reports.index')->with('status', 'تم تحديث السجل التدريبي تلقائياً');
    }

    public function opportunityReport(Opportunity $opportunity)
    {
        $nominations = Nomination::with(['employee'])
            ->where('opportunity_id', $opportunity->id)
            ->orderByDesc('id')
            ->get();

        return view('reports.opportunity', compact('opportunity', 'nominations'));
    }

    public function opportunityPrint(Opportunity $opportunity, Request $request)
    {
        $nominations = Nomination::with(['employee'])
            ->where('opportunity_id', $opportunity->id)
            ->orderByDesc('id')
            ->get();
        $withReasons = $request->boolean('reasons', true);

        return view('reports.opportunity_print', compact('opportunity', 'nominations', 'withReasons'));
    }
}
