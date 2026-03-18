<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Nomination;
use App\Models\Opportunity;
use App\Models\ApplicationRequest;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $canManageApprovals = $user?->hasRole('system_admin') ?? false;

        if ($user?->employee_id) {
            return redirect()->route('portal.dashboard');
        }

        $stats = [
            'opportunities_total' => Opportunity::count(),
            'opportunities_open' => Opportunity::where('status', 'open_for_nomination')->count(),
            'applications_pending' => ApplicationRequest::whereIn('status', ['submitted', 'under_review'])->count(),
            'nominations_total' => Nomination::count(),
            'employees_total' => Employee::count(),
            'pending_employee_approvals' => User::whereNotNull('employee_id')->where('approval_status', 'pending')->count(),
        ];

        $latestOpportunities = Opportunity::with('partner')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $latestNominations = Nomination::with(['employee', 'opportunity'])
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $pendingEmployees = $canManageApprovals
            ? User::with('employee')
                ->whereNotNull('employee_id')
                ->where('approval_status', 'pending')
                ->orderByDesc('id')
                ->take(6)
                ->get()
            : collect();

        $attentionOpportunities = Opportunity::query()
            ->where('status', 'open_for_nomination')
            ->withCount([
                'applicationRequests',
                'applicationRequests as pending_applications_count' => fn ($query) => $query->whereIn('status', ['submitted', 'under_review']),
                'applicationRequests as approved_applications_count' => fn ($query) => $query->where('status', 'approved'),
            ])
            ->orderBy('nomination_deadline')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'stats',
            'latestOpportunities',
            'latestNominations',
            'pendingEmployees',
            'attentionOpportunities',
            'canManageApprovals'
        ));
    }
}
