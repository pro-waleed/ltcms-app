<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use App\Models\Employee;
use App\Models\Nomination;
use App\Models\Opportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationRequestController extends Controller
{
    public function index(Request $request)
    {
        $applications = ApplicationRequest::with(['employee', 'opportunity', 'nomination'])
            ->when($request->filled('opportunity_id'), fn ($query) => $query->where('opportunity_id', (int) $request->input('opportunity_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        $opportunities = Opportunity::orderByDesc('id')->get();
        $statuses = $this->statuses();

        return view('applications.index', compact('applications', 'opportunities', 'statuses'));
    }

    public function create()
    {
        $employees = Employee::orderBy('full_name')->get();
        $opportunities = Opportunity::orderByDesc('id')->get();
        $statuses = $this->statuses();

        return view('applications.create', compact('employees', 'opportunities', 'statuses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'opportunity_id' => ['required', 'exists:opportunities,id'],
            'request_date' => ['nullable', 'date'],
            'status' => ['required', 'in:submitted,under_review,approved,rejected,withdrawn'],
            'decision_reason' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($data) {
            $application = ApplicationRequest::create($data);
            $this->syncNominationFromApplication($application);
        });

        return redirect()->route('applications.index')
            ->with('status', 'تمت إضافة طلب المشاركة بنجاح.');
    }

    public function edit(ApplicationRequest $application)
    {
        $employees = Employee::orderBy('full_name')->get();
        $opportunities = Opportunity::orderByDesc('id')->get();
        $statuses = $this->statuses();

        return view('applications.edit', compact('application', 'employees', 'opportunities', 'statuses'));
    }

    public function update(Request $request, ApplicationRequest $application)
    {
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'opportunity_id' => ['required', 'exists:opportunities,id'],
            'request_date' => ['nullable', 'date'],
            'status' => ['required', 'in:submitted,under_review,approved,rejected,withdrawn'],
            'decision_reason' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($application, $data) {
            $application->update($data);
            $this->syncNominationFromApplication($application->fresh());
        });

        return redirect()->route('applications.index')
            ->with('status', 'تم تحديث طلب المشاركة بنجاح.');
    }

    public function destroy(ApplicationRequest $application)
    {
        $application->delete();

        return redirect()->route('applications.index')
            ->with('status', 'تم حذف طلب المشاركة.');
    }

    private function statuses(): array
    {
        return ApplicationRequest::statusLabels();
    }

    private function syncNominationFromApplication(ApplicationRequest $application): void
    {
        $nomination = Nomination::firstOrNew([
            'application_request_id' => $application->id,
        ]);

        if ($application->status === 'approved') {
            if (!$nomination->exists) {
                $nomination->nomination_no = Nomination::nextNumber();
            }

            $nomination->fill([
                'opportunity_id' => $application->opportunity_id,
                'employee_id' => $application->employee_id,
                'nominated_by_department_id' => $application->employee?->department_id,
                'nomination_date' => $application->request_date ?? now()->toDateString(),
                'nomination_type' => 'application',
                'status' => 'nominated',
                'selection_category' => $nomination->selection_category,
                'rank_order' => $nomination->rank_order,
                'nomination_reason' => $application->decision_reason,
                'notes' => $application->notes,
            ]);

            $nomination->save();
            return;
        }

        if (!$nomination->exists) {
            return;
        }

        $mappedStatus = match ($application->status) {
            'under_review' => 'under_review',
            'rejected' => 'rejected',
            'withdrawn' => 'declined',
            default => 'under_review',
        };

        $nomination->update([
            'status' => $mappedStatus,
            'nomination_reason' => $application->decision_reason,
            'notes' => $application->notes,
        ]);
    }
}
