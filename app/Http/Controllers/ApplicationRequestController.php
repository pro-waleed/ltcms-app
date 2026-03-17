<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use App\Models\Employee;
use App\Models\Opportunity;
use Illuminate\Http\Request;

class ApplicationRequestController extends Controller
{
    public function index()
    {
        $applications = ApplicationRequest::with(['employee', 'opportunity'])
            ->orderByDesc('id')
            ->paginate(25);

        return view('applications.index', compact('applications'));
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
            'employee_id' => ['nullable', 'exists:employees,id'],
            'opportunity_id' => ['required', 'exists:opportunities,id'],
            'request_date' => ['nullable', 'date'],
            'status' => ['required', 'in:submitted,under_review,approved,rejected,withdrawn'],
            'decision_reason' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        ApplicationRequest::create($data);

        return redirect()->route('applications.index')
            ->with('status', 'تمت إضافة طلب المشاركة.');
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
            'employee_id' => ['nullable', 'exists:employees,id'],
            'opportunity_id' => ['required', 'exists:opportunities,id'],
            'request_date' => ['nullable', 'date'],
            'status' => ['required', 'in:submitted,under_review,approved,rejected,withdrawn'],
            'decision_reason' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $application->update($data);

        return redirect()->route('applications.index')
            ->with('status', 'تم تحديث طلب المشاركة.');
    }

    public function destroy(ApplicationRequest $application)
    {
        $application->delete();

        return redirect()->route('applications.index')
            ->with('status', 'تم حذف طلب المشاركة.');
    }

    private function statuses(): array
    {
        return [
            'submitted' => 'مقدم',
            'under_review' => 'قيد المراجعة',
            'approved' => 'مقبول',
            'rejected' => 'مرفوض',
            'withdrawn' => 'منسحب',
        ];
    }
}
