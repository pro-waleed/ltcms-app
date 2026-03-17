<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Mission;
use App\Models\Nomination;
use App\Models\Opportunity;
use App\Models\OpportunityType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NominationController extends Controller
{
    public function index()
    {
        $nominations = Nomination::with(['opportunity', 'employee', 'nominatedByDepartment'])
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

        $data['nomination_no'] = $this->nextNominationNumber();

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
        $nominations = collect();

        if ($request->filled('opportunity_id')) {
            $selectedOpportunity = Opportunity::findOrFail($request->input('opportunity_id'));
            $nominations = Nomination::with(['employee'])
                ->where('opportunity_id', $selectedOpportunity->id)
                ->orderByDesc('id')
                ->get();
        }

        return view('nominations.by_opportunity', compact('opportunities', 'selectedOpportunity', 'nominations'));
    }

    public function updateByOpportunity(Request $request)
    {
        $data = $request->validate([
            'opportunity_id' => ['required', 'exists:opportunities,id'],
            'status' => ['array'],
            'status.*' => ['nullable', 'in:nominated,under_review,approved,reserve,rejected,declined,attended,not_attended,completed,closed'],
            'nomination_reason' => ['array'],
            'nomination_reason.*' => ['nullable', 'string'],
        ]);

        $opportunityId = (int) $data['opportunity_id'];
        $status = $data['status'] ?? [];
        $reasons = $data['nomination_reason'] ?? [];

        $nominations = Nomination::where('opportunity_id', $opportunityId)->get();
        foreach ($nominations as $nomination) {
            $id = $nomination->id;
            $updates = [];
            if (array_key_exists($id, $status) && $status[$id] !== null && $status[$id] !== '') {
                $updates['status'] = $status[$id];
            }
            if (array_key_exists($id, $reasons)) {
                $updates['nomination_reason'] = $reasons[$id];
            }
            if (!empty($updates)) {
                $nomination->update($updates);
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
            $statusRaw = trim((string) ($payload['status'] ?? 'nominated'));
            $statusMap = [
                'مرشح' => 'nominated',
                'قيد المراجعة' => 'under_review',
                'معتمد' => 'approved',
                'احتياطي' => 'reserve',
                'مرفوض' => 'rejected',
                'معتذر' => 'declined',
                'شارك' => 'attended',
                'لم يشارك' => 'not_attended',
                'مكتمل' => 'completed',
                'مغلق' => 'closed',
            ];
            $statusKey = mb_strtolower($statusRaw);
            $status = $statusMap[$statusRaw] ?? $statusMap[$statusKey] ?? $statusKey;
            $allowedStatuses = ['nominated','under_review','approved','reserve','rejected','declined','attended','not_attended','completed','closed'];
            if ($status === '' || !in_array($status, $allowedStatuses, true)) {
                $status = 'nominated';
            }
            $reason = trim((string) ($payload['nomination_reason'] ?? ''));
            $notes = trim((string) ($payload['notes'] ?? ''));

            if ($fullName === '' || ($opportunityTitle === '' && $opportunityReference === '')) {
                continue;
            }

            $department = null;
            if ($departmentName !== '') {
                $department = Department::firstOrCreate(['name' => $departmentName]);
            }

            $mission = null;
            if ($missionName !== '') {
                $mission = Mission::firstOrCreate(['name' => $missionName]);
            }

            $employee = Employee::query()
                ->when($employeeNo !== '', fn ($q) => $q->where('employee_no', $employeeNo))
                ->when($employeeNo === '', fn ($q) => $q->where('full_name', $fullName))
                ->first();

            if (!$employee) {
                $employee = Employee::create([
                    'employee_no' => $employeeNo !== '' ? $employeeNo : $this->generateEmployeeNo(),
                    'full_name' => $fullName,
                    'department_id' => $department?->id,
                    'mission_id' => $mission?->id,
                    'job_title' => $jobTitle,
                ]);
            }

            if ($department && !$employee->department_id) {
                $employee->update(['department_id' => $department->id]);
            }
            if ($mission && !$employee->mission_id) {
                $employee->update(['mission_id' => $mission->id]);
            }
            if ($jobTitle !== '' && !$employee->job_title) {
                $employee->update(['job_title' => $jobTitle]);
            }

            $opportunity = Opportunity::query()
                ->when($opportunityReference !== '', fn ($q) => $q->where('reference_no', $opportunityReference))
                ->when($opportunityReference === '', fn ($q) => $q->where('title', $opportunityTitle))
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
                $nomination->nomination_no = $this->nextNominationNumber();
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

    private function nextNominationNumber(): string
    {
        $year = date('Y');
        $prefix = "NOM-$year-";
        $last = Nomination::where('nomination_no', 'like', $prefix . '%')
            ->orderByDesc('nomination_no')
            ->first();

        $nextNumber = 1;
        if ($last) {
            $suffix = Str::after($last->nomination_no, $prefix);
            $nextNumber = max(1, intval($suffix) + 1);
        }

        return $prefix . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }

    private function generateEmployeeNo(): string
    {
        do {
            $candidate = 'EMP-' . date('Ymd') . '-' . str_pad((string) rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Employee::where('employee_no', $candidate)->exists());

        return $candidate;
    }
}
