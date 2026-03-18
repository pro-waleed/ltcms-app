<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Nomination;
use App\Models\Opportunity;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user?->employee_id) {
            return redirect()->route('portal.dashboard');
        }

        $stats = [
            'opportunities_total' => Opportunity::count(),
            'opportunities_open' => Opportunity::where('status', 'open_for_nomination')->count(),
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

        $pendingEmployees = User::with('employee')
            ->whereNotNull('employee_id')
            ->where('approval_status', 'pending')
            ->orderByDesc('id')
            ->take(6)
            ->get();

        return view('dashboard', compact('stats', 'latestOpportunities', 'latestNominations', 'pendingEmployees'));
    }
}
