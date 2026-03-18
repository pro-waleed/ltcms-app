<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Mission;
use App\Models\Opportunity;
use App\Models\Partner;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $stored = session('search.filters', []);
        $filters = $request->all();

        $hasInput = false;
        foreach ($filters as $value) {
            if (is_array($value)) {
                if (!empty(array_filter($value, fn ($v) => $v !== null && $v !== ''))) {
                    $hasInput = true;
                    break;
                }
                continue;
            }
            if ($value !== null && $value !== '') {
                $hasInput = true;
                break;
            }
        }

        if ($hasInput) {
            session(['search.filters' => $filters]);
        } elseif (!empty($stored)) {
            $filters = $stored;
        }

        $query = trim((string) ($filters['q'] ?? ''));
        $type = $filters['type'] ?? 'all';

        $departmentId = $filters['department_id'] ?? null;
        $missionId = $filters['mission_id'] ?? null;
        $jobTitle = trim((string) ($filters['job_title'] ?? ''));

        $opportunityStatus = $filters['opportunity_status'] ?? null;
        $opportunityPartnerId = $filters['opportunity_partner_id'] ?? null;
        $opportunityMode = $filters['opportunity_mode'] ?? null;
        $opportunityYear = $filters['opportunity_year'] ?? null;

        $partnerCountry = trim((string) ($filters['partner_country'] ?? ''));
        $partnerType = trim((string) ($filters['partner_type'] ?? ''));

        $employees = collect();
        $opportunities = collect();
        $partners = collect();

        $departments = Department::orderBy('name')->get();
        $missions = Mission::orderBy('name')->get();
        $partnersList = Partner::orderBy('name')->get();

        if ($query !== '') {
            if ($type === 'all' || $type === 'employees') {
                $employees = Employee::query()
                    ->when($departmentId, fn ($q) => $q->where('department_id', $departmentId))
                    ->when($missionId, fn ($q) => $q->where('mission_id', $missionId))
                    ->when($jobTitle !== '', fn ($q) => $q->where('job_title', 'like', "%{$jobTitle}%"))
                    ->where(function ($q) use ($query) {
                        $q->where('full_name', 'like', "%{$query}%")
                            ->orWhere('employee_no', 'like', "%{$query}%")
                            ->orWhere('job_title', 'like', "%{$query}%")
                            ->orWhere('specialization', 'like', "%{$query}%");
                    })
                    ->orderBy('full_name')
                    ->limit(50)
                    ->get();
            }

            if ($type === 'all' || $type === 'opportunities') {
                $opportunities = Opportunity::query()
                    ->when($opportunityStatus, fn ($q) => $q->where('status', $opportunityStatus))
                    ->when($opportunityPartnerId, fn ($q) => $q->where('partner_id', $opportunityPartnerId))
                    ->when($opportunityMode, fn ($q) => $q->where('delivery_mode', $opportunityMode))
                    ->when($opportunityYear, fn ($q) => $q->whereYear('start_date', (int) $opportunityYear))
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                            ->orWhere('reference_no', 'like', "%{$query}%")
                            ->orWhere('summary', 'like', "%{$query}%");
                    })
                    ->orderByDesc('id')
                    ->limit(50)
                    ->get();
            }

            if ($type === 'all' || $type === 'partners') {
                $partners = Partner::query()
                    ->when($partnerCountry !== '', fn ($q) => $q->where('country', 'like', "%{$partnerCountry}%"))
                    ->when($partnerType !== '', fn ($q) => $q->where('partner_type', 'like', "%{$partnerType}%"))
                    ->where(function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%")
                            ->orWhere('partner_type', 'like', "%{$query}%")
                            ->orWhere('country', 'like', "%{$query}%");
                    })
                    ->orderBy('name')
                    ->limit(50)
                    ->get();
            }
        }

        return view('search.index', compact(
            'query',
            'type',
            'departmentId',
            'missionId',
            'jobTitle',
            'opportunityStatus',
            'opportunityPartnerId',
            'opportunityMode',
            'opportunityYear',
            'partnerCountry',
            'partnerType',
            'departments',
            'missions',
            'partnersList',
            'employees',
            'opportunities',
            'partners'
        ));
    }

    public function suggest(Request $request)
    {
        $query = trim((string) $request->input('q', ''));
        if (mb_strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $employees = Employee::query()
            ->where('full_name', 'like', "%{$query}%")
            ->orWhere('employee_no', 'like', "%{$query}%")
            ->orderBy('full_name')
            ->limit(6)
            ->get();

        $opportunities = Opportunity::query()
            ->where('title', 'like', "%{$query}%")
            ->orWhere('reference_no', 'like', "%{$query}%")
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        $partners = Partner::query()
            ->where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(6)
            ->get();

        $results = [];
        foreach ($employees as $employee) {
            $results[] = [
                'type' => 'employee',
                'label' => $employee->full_name . ($employee->employee_no ? " ({$employee->employee_no})" : ''),
                'url' => route('employees.edit', $employee),
            ];
        }
        foreach ($opportunities as $opportunity) {
            $results[] = [
                'type' => 'opportunity',
                'label' => $opportunity->title . ($opportunity->reference_no ? " ({$opportunity->reference_no})" : ''),
                'url' => route('opportunities.edit', $opportunity),
            ];
        }
        foreach ($partners as $partner) {
            $results[] = [
                'type' => 'partner',
                'label' => $partner->name,
                'url' => route('partners.edit', $partner),
            ];
        }

        return response()->json(['results' => $results]);
    }

    public function employees(Request $request)
    {
        $query = trim((string) $request->input('q', ''));
        if (mb_strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $employees = Employee::query()
            ->with('department')
            ->where(function ($builder) use ($query) {
                $builder->where('full_name', 'like', "%{$query}%")
                    ->orWhere('employee_no', 'like', "%{$query}%")
                    ->orWhere('job_title', 'like', "%{$query}%");
            })
            ->orderBy('full_name')
            ->limit(12)
            ->get();

        $results = $employees->map(function ($employee) {
            return [
                'id' => $employee->id,
                'name' => $employee->full_name,
                'employee_no' => $employee->employee_no,
                'department' => optional($employee->department)->name,
                'job_title' => $employee->job_title,
                'label' => trim($employee->full_name . ' - ' . ($employee->employee_no ?: 'بدون رقم')),
            ];
        })->values();

        return response()->json(['results' => $results]);
    }
}
