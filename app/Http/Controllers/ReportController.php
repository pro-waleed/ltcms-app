<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
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
        $base = Opportunity::with(['type', 'partner'])
            ->withCount([
                'applicationRequests',
                'applicationRequests as approved_applications_count' => fn ($query) => $query->where('status', 'approved'),
                'nominations',
            ]);

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
        $this->decorateOpportunityRows($opportunities);

        $stats = [
            'opportunities_total' => Opportunity::count(),
            'opportunities_open' => Opportunity::where('status', 'open_for_nomination')->count(),
            'applications_total' => ApplicationRequest::count(),
            'applications_pending' => ApplicationRequest::whereIn('status', ['submitted', 'under_review'])->count(),
            'applications_approved' => ApplicationRequest::where('status', 'approved')->count(),
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
        $query = Opportunity::with('type')
            ->withCount([
                'applicationRequests',
                'applicationRequests as approved_applications_count' => fn ($base) => $base->where('status', 'approved'),
                'nominations',
            ]);

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
            fputcsv($output, ['Reference', 'Title', 'Type', 'Mode', 'Status', 'Applications', 'Approved Applications', 'Nominations']);
            foreach ($rows as $row) {
                fputcsv($output, [
                    $row->reference_no,
                    $row->title,
                    optional($row->type)->name,
                    $row->delivery_mode,
                    $row->status,
                    $row->application_requests_count,
                    $row->approved_applications_count,
                    $row->nominations_count,
                ]);
            }
            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $query = Opportunity::query()->withCount([
            'applicationRequests',
            'nominations',
        ]);

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

        return redirect()->route('reports.index')->with('status', 'تم تحديث السجل التدريبي تلقائيًا.');
    }

    public function opportunityReport(Opportunity $opportunity)
    {
        $applications = ApplicationRequest::with(['employee.department', 'nomination'])
            ->where('opportunity_id', $opportunity->id)
            ->orderByDesc('id')
            ->get();

        $primaryCount = $applications->filter(fn ($application) => optional($application->nomination)->selection_category === 'primary')->count();
        $reserveCount = $applications->filter(fn ($application) => optional($application->nomination)->selection_category === 'reserve')->count();
        $rejectedCount = $applications->where('status', 'rejected')->count();
        $approvedUnassignedCount = $applications->filter(function ($application) {
            return $application->status === 'approved'
                && $application->nomination
                && empty($application->nomination->selection_category);
        })->count();
        $seats = $opportunity->seats ?: 0;

        $decisionLabel = 'مكتمل';
        if ($approvedUnassignedCount > 0) {
            $decisionLabel = 'توجد طلبات مقبولة بانتظار التصنيف النهائي';
        } elseif ($applications->whereIn('status', ['submitted', 'under_review'])->count() > 0) {
            $decisionLabel = 'توجد طلبات ما زالت قيد المراجعة';
        } elseif ($seats > 0 && $primaryCount < $seats) {
            $decisionLabel = 'عدد المرشحين الأساسيين أقل من المقاعد المتاحة';
        } elseif ($seats > 0 && $primaryCount > $seats) {
            $decisionLabel = 'عدد المرشحين الأساسيين يتجاوز المقاعد المتاحة';
        }

        $summary = [
            'applications_total' => $applications->count(),
            'applications_pending' => $applications->whereIn('status', ['submitted', 'under_review'])->count(),
            'applications_approved' => $applications->where('status', 'approved')->count(),
            'applications_rejected' => $applications->where('status', 'rejected')->count(),
            'nominations_total' => $applications->filter(fn ($application) => $application->nomination)->count(),
            'primary_count' => $primaryCount,
            'reserve_count' => $reserveCount,
            'rejected_count' => $rejectedCount,
            'approved_unassigned_count' => $approvedUnassignedCount,
            'seats' => $seats,
            'seat_gap' => max(0, $seats - $primaryCount),
            'seat_surplus' => max(0, $primaryCount - $seats),
            'decision_label' => $decisionLabel,
        ];

        return view('reports.opportunity', compact('opportunity', 'applications', 'summary'));
    }

    public function opportunityPrint(Opportunity $opportunity, Request $request)
    {
        $applications = ApplicationRequest::with(['employee', 'nomination'])
            ->where('opportunity_id', $opportunity->id)
            ->orderByDesc('id')
            ->get();
        $withReasons = $request->boolean('reasons', true);

        return view('reports.opportunity_print', compact('opportunity', 'applications', 'withReasons'));
    }

    public function opportunityDecision(Opportunity $opportunity)
    {
        [$applications, $decision] = $this->buildOpportunityDecisionData($opportunity);

        return view('reports.opportunity_decision', compact('opportunity', 'applications', 'decision'));
    }

    public function opportunityDecisionPrint(Opportunity $opportunity)
    {
        [$applications, $decision] = $this->buildOpportunityDecisionData($opportunity);

        return view('reports.opportunity_decision_print', compact('opportunity', 'applications', 'decision'));
    }

    private function buildOpportunityDecisionData(Opportunity $opportunity): array
    {
        $applications = ApplicationRequest::with(['employee.department', 'nomination'])
            ->where('opportunity_id', $opportunity->id)
            ->orderByRaw('case when status = ? then 0 when status = ? then 1 else 2 end', ['approved', 'under_review'])
            ->orderByDesc('id')
            ->get();

        $primary = $applications
            ->filter(fn ($application) => optional($application->nomination)->selection_category === 'primary')
            ->sortBy(fn ($application) => $application->nomination?->rank_order ?? PHP_INT_MAX)
            ->values();

        $reserve = $applications
            ->filter(fn ($application) => optional($application->nomination)->selection_category === 'reserve')
            ->sortBy(fn ($application) => $application->nomination?->rank_order ?? PHP_INT_MAX)
            ->values();

        $rejected = $applications
            ->filter(fn ($application) => in_array($application->status, ['rejected', 'withdrawn'], true))
            ->values();

        $pending = $applications
            ->filter(fn ($application) => in_array($application->status, ['submitted', 'under_review'], true))
            ->values();

        $approvedUnassigned = $applications
            ->filter(function ($application) {
                return $application->status === 'approved'
                    && $application->nomination
                    && empty($application->nomination->selection_category);
            })
            ->values();

        $seats = $opportunity->seats ?: 0;
        $primaryCount = $primary->count();

        return [
            $applications,
            [
                'primary' => $primary,
                'reserve' => $reserve,
                'rejected' => $rejected,
                'pending' => $pending,
                'seats' => $seats,
                'primary_count' => $primaryCount,
                'reserve_count' => $reserve->count(),
                'rejected_count' => $rejected->count(),
                'pending_count' => $pending->count(),
                'approved_unassigned' => $approvedUnassigned,
                'approved_unassigned_count' => $approvedUnassigned->count(),
                'seat_gap' => max(0, $seats - $primaryCount),
                'seat_surplus' => max(0, $primaryCount - $seats),
            ],
        ];
    }

    private function decorateOpportunityRows($paginator): void
    {
        $paginator->getCollection()->load(['applicationRequests.nomination']);

        $paginator->setCollection(
            $paginator->getCollection()->map(function (Opportunity $opportunity) {
                $applications = $opportunity->applicationRequests;
                $primaryCount = $applications->filter(
                    fn ($application) => optional($application->nomination)->selection_category === 'primary'
                )->count();
                $reserveCount = $applications->filter(
                    fn ($application) => optional($application->nomination)->selection_category === 'reserve'
                )->count();
                $approvedUnassignedCount = $applications->filter(function ($application) {
                    return $application->status === 'approved'
                        && $application->nomination
                        && empty($application->nomination->selection_category);
                })->count();
                $pendingCount = $applications->whereIn('status', ['submitted', 'under_review'])->count();
                $seats = (int) ($opportunity->seats ?? 0);

                $decisionState = 'ready';
                $decisionLabel = 'مكتمل';

                if ($approvedUnassignedCount > 0) {
                    $decisionState = 'needs_assignment';
                    $decisionLabel = 'ينتظر تصنيف المقبولين';
                } elseif ($pendingCount > 0) {
                    $decisionState = 'pending_review';
                    $decisionLabel = 'توجد طلبات قيد المراجعة';
                } elseif ($seats > 0 && $primaryCount < $seats) {
                    $decisionState = 'under_capacity';
                    $decisionLabel = 'عدد الأساسيين أقل من المقاعد';
                } elseif ($seats > 0 && $primaryCount > $seats) {
                    $decisionState = 'over_capacity';
                    $decisionLabel = 'عدد الأساسيين يتجاوز المقاعد';
                }

                $opportunity->setAttribute('primary_count', $primaryCount);
                $opportunity->setAttribute('reserve_count', $reserveCount);
                $opportunity->setAttribute('approved_unassigned_count', $approvedUnassignedCount);
                $opportunity->setAttribute('pending_applications_count', $pendingCount);
                $opportunity->setAttribute('decision_state', $decisionState);
                $opportunity->setAttribute('decision_label', $decisionLabel);

                return $opportunity;
            })
        );
    }
}
