<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Nomination;
use App\Models\Opportunity;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'opportunities_total' => Opportunity::count(),
            'opportunities_open' => Opportunity::where('status', 'open_for_nomination')->count(),
            'nominations_total' => Nomination::count(),
            'employees_total' => Employee::count(),
        ];

        $latestOpportunities = Opportunity::with('partner')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $latestNominations = Nomination::with(['employee', 'opportunity'])
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'latestOpportunities', 'latestNominations'));
    }
}
