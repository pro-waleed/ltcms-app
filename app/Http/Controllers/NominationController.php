<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Mission;
use App\Models\Nomination;
use App\Models\Opportunity;
use App\Models\OpportunityType;
use Illuminate\Http\Request;

class NominationController extends Controller
{
    public function index()
    {
        $nominations = Nomination::with(['opportunity', 'employee', 'applicationRequest', 'nominatedByDepartment'])
            ->orderByDesc('id')
            ->paginate(25);

        return view('nominations.index', compact('nominations'));
    }

    public function create()
    {
        $opportunities = Opportunity::orderByDesc('id')->get();
        $employees = Employee::orderBy('full_name')->get();
        $departments = Department::orderBy('name')->get();

        return view('nominations.create', compact('opportunities', 'employees', 'departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'opportunity_id' => ['required', 'exists:opportunities,id'],
            'employee_id' => ['required', 'exists:employees,id'],
            'nominated_by_department_id' => ['nullable', 'exists:departments,id'],
            'nomination_date' => ['nullable', 'date'],
            'nomination_type' => ['nullable', 'string', 'max:80'],
            'status' => ['required', 'in:nominated,under_review,approved,reserve,rejected,declined,attended,not_attended,completed,closed'],
            'notes' => ['nullable', 'string'],
            'nomination_reason' => ['nullable', 'string'],
        ]);

        $data['nomination_no'] = Nomination::nextNumber();

        Nomination::create($data);

        return redirect()->route('nominations.index')
            ->with('status', 'تمت إضافة الترشيح بنجاح');
    }

    public function edit(Nomination $nomination)
    {
        $opportunities = Opportunity::orderByDesc('id')->get();
        $employees = Employee::orderBy('full_name')->get();
        $departments = Department::orderBy('name')->get();

        return view('nominations.edit', compact('nomination', 'opportunities', 'employees', 'departments'));
    }

    public function update(Request $request, Nomination $nomination)
    {
        $data = $request->validate([
            'opportunity_id' => ['required', 'exists:opportunities,id'],
            'employee_id' => ['required', 'exists:employees,id'],
            'nominated_by_department_id' => ['nullable', 'exists:departments,id'],
            'nomination_date' => ['nullable', 'date'],
            'nomination_type' => ['nullable', 'string', 'max:80'],
            'status' => ['required', 'in:nominated,under_review,approved,reserve,rejected,declined,attended,not_attended,completed,closed'],
            'notes' => ['nullable', 'string'],
            'nomination_reason' => ['nullable', 'string'],
        ]);

        $nomination->update($data);

        return redirect()->route('nominations.index')
            ->with('status', 'تم تحديث الترشيح');
    }

    public function byOpportunity(Request $request)
    {
        $opportunities = Opportunity::orderByDesc('id')->get();
        $selectedOpportunity = null;
        $applications = collect();
        $applicationStatuses = ApplicationRequest::statusLabels();
        $nominationStatuses = Nomination::statusLabels();

        if ($request->filled('opportunity_id')) {
            $selectedOpportunity = Opportunity::findOrFail($request->input('opportunity_id'));
            $applications = ApplicationRequest::with(['employee.department', 'nomination'])
                ->where('opportunity_id', $selectedOpportunity->id)
                ->orderByDesc('id')
                ->get();
        }

        return view('nominations.by_opportunity', compact(
            'opportunities',
            'selectedOpportunity',
            'applications',
            'applicationStatuses',
            'nominationStatuses'
        ));
    }

    public function updateByOpportunity(Request $request)
    {
        $data = $request->validate([
            'opportunity_id' => ['required', 'exists:opportunities,id'],
            'application_status' => ['array'],
            'application_status.*' => ['nullable', 'in:submitted,under_review,approved,rejected,withdrawn'],
            'decision_reason' => ['array'],
            'decision_reason.*' => ['nullable', 'string'],
            'notes' => ['array'],
            'notes.*' => ['nullable', 'string'],
        ]);

        $opportunityId = (int) $data['opportunity_id'];
        $statuses = $data['application_status'] ?? [];
        $reasons = $data['decision_reason'] ?? [];
        $notes = $data['notes'] ?? [];

        $applications = ApplicationRequest::where('opportunity_id', $opportunityId)->get();
        foreach ($applications as $application) {
            $id = $application->id;
            $updates = [];

            if (array_key_exists($id, $statuses) && $statuses[$id] !== null && $statuses[$id] !== '') {
                $updates['status'] = $statuses[$id];
            }
            if (array_key_exists($id, $reasons)) {
                $updates['decision_reason'] = $reasons[$id];
            }
            if (array_key_exists($id, $notes)) {
                $updates['notes'] = $notes[$id];
            }

            if (!empty($updates)) {
                $application->update($updates);
                $this->syncNominationFromApplication($application->fresh(['employee']));
            }
        }

        return redirect()->route('nominations.by-opportunity', ['opportunity_id' => $opportunityId])
            ->with('status', 'تم تحديث حالات المتقدمين بنجاح');
    }

    public function importForm()
    {
        return view('nominations.import');
    }

    public function importTemplate()
    {
        $headers = [
            'full_name',
            'employee_no',
            'department',
            'mission',
            'job_title',
            'opportunity_title',
            'opportunity_reference',
            'nomination_date',
            'status',
            'nomination_reason',
            'notes',
        ];

        $callback = function () use ($headers) {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, $headers);
            fclose($output);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="ltcms-nominations-template.csv"',
        ]);
    }

    public function import(Request $request)
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $path = $data['file']->getRealPath();
        $handle = fopen($path, 'r');
        if (!$handle) {
            return redirect()->route('nominations.import.form')->with('status', 'تعذر قراءة الملف.');
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return redirect()->route('nominations.import.form')->with('status', 'ملف الاستيراد فارغ.');
        }

        $header = array_map(fn ($value) => trim(mb_strtolower($value)), $header);
        $rows = 0;
        $created = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $rows++;
            $payload = [];
            foreach ($header as $index => $key) {
                $payload[$key] = $row[$index] ?? null;
            }

            $fullName = trim((string) ($payload['full_name'] ?? ''));
            $employeeNo = trim((string) ($payload['employee_no'] ?? ''));
            $departmentName = trim((string) ($payload['department'] ?? ''));
            $missionName = trim((string) ($payload['mission'] ?? ''));
            $jobTitle = trim((string) ($payload['job_title'] ?? ''));
            $opportunityTitle = trim((string) ($payload['opportunity_title'] ?? ''));
            $opportunityReference = trim((string) ($payload['opportunity_reference'] ?? ''));
            $nominationDate = trim((string) ($payload['nomination_date'] ?? ''));
            $status = trim((string) ($payload['status'] ?? 'nominated'));
            $reason = trim((string) ($payload['nomination_reason'] ?? ''));
            $notes = trim((string) ($payload['notes'] ?? ''));

            if ($fullName === '' || ($opportunityTitle === '' && $opportunityReference === '')) {
                continue;
            }

            $department = $departmentName !== '' ? Department::firstOrCreate(['name' => $departmentName]) : null;
            $mission = $missionName !== '' ? Mission::firstOrCreate(['name' => $missionName]) : null;

            $employee = Employee::query()
                ->when($employeeNo !== '', fn ($query) => $query->where('employee_no', $employeeNo))
                ->when($employeeNo === '', fn ($query) => $query->where('full_name', $fullName))
                ->first();

            if (!$employee) {
                $employee = Employee::create([
                    'employee_no' => $employeeNo !== '' ? $employeeNo : Employee::nextEmployeeNumber(),
                    'full_name' => $fullName,
                    'department_id' => $department?->id,
                    'mission_id' => $mission?->id,
                    'job_title' => $jobTitle,
                ]);
            }

            $opportunity = Opportunity::query()
                ->when($opportunityReference !== '', fn ($query) => $query->where('reference_no', $opportunityReference))
                ->when($opportunityReference === '', fn ($query) => $query->where('title', $opportunityTitle))
                ->first();

            if (!$opportunity) {
                $opportunityTypeId = OpportunityType::query()->value('id');

                $opportunity = Opportunity::create([
                    'reference_no' => $opportunityReference !== '' ? $opportunityReference : 'TR-' . date('Y') . '-' . str_pad((string) (Opportunity::count() + 1), 3, '0', STR_PAD_LEFT),
                    'title' => $opportunityTitle,
                    'opportunity_type_id' => $opportunityTypeId,
                    'status' => 'open_for_nomination',
                    'delivery_mode' => 'onsite',
                ]);
            }

            $nomination = Nomination::firstOrNew([
                'employee_id' => $employee->id,
                'opportunity_id' => $opportunity->id,
            ]);

            if (!$nomination->exists) {
                $nomination->nomination_no = Nomination::nextNumber();
            }

            $nomination->nomination_date = $nominationDate !== '' ? $nominationDate : $nomination->nomination_date;
            $nomination->status = $status;
            $nomination->nomination_reason = $reason;
            $nomination->notes = $notes;
            $nomination->save();

            $created++;
        }

        fclose($handle);

        return redirect()->route('nominations.import.form')
            ->with('status', "تم استيراد {$created} سجل من أصل {$rows}.");
    }

    public function destroy(Nomination $nomination)
    {
        $nomination->update(['status' => 'closed']);

        return redirect()->route('nominations.index')
            ->with('status', 'تم إغلاق الترشيح');
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
                'nomination_reason' => $application->decision_reason,
                'notes' => $application->notes,
            ]);

            $nomination->save();
            return;
        }

        if (!$nomination->exists) {
            return;
        }

        $nomination->update([
            'status' => match ($application->status) {
                'under_review' => 'under_review',
                'rejected' => 'rejected',
                'withdrawn' => 'declined',
                default => 'under_review',
            },
            'nomination_reason' => $application->decision_reason,
            'notes' => $application->notes,
        ]);
    }
}
