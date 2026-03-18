<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use App\Models\Department;
use App\Models\Mission;
use App\Models\Opportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeePortalController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $employee = $user->employee()
            ->with(['department'])
            ->withCount(['applicationRequests', 'trainingHistory'])
            ->firstOrFail();

        $openOpportunities = Opportunity::query()
            ->where('status', 'open_for_nomination')
            ->where(function ($query) {
                $query->whereNull('nomination_deadline')
                    ->orWhereDate('nomination_deadline', '>=', now()->toDateString());
            })
            ->orderBy('nomination_deadline')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $latestApplications = $employee->applicationRequests()
            ->with('opportunity')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('portal.dashboard', compact('employee', 'openOpportunities', 'latestApplications', 'user'));
    }

    public function opportunities(Request $request)
    {
        $employee = $request->user()->employee;

        $opportunities = Opportunity::query()
            ->where('status', 'open_for_nomination')
            ->where(function ($query) {
                $query->whereNull('nomination_deadline')
                    ->orWhereDate('nomination_deadline', '>=', now()->toDateString());
            })
            ->with(['partner', 'type'])
            ->orderBy('nomination_deadline')
            ->orderByDesc('id')
            ->paginate(12);

        $appliedIds = $employee->applicationRequests()->pluck('opportunity_id')->all();
        $canApply = $request->user()->isApprovedForOpportunities();

        return view('portal.opportunities', compact('opportunities', 'appliedIds', 'canApply'));
    }

    public function apply(Request $request, Opportunity $opportunity)
    {
        $employee = $request->user()->employee;

        abort_unless($opportunity->status === 'open_for_nomination', 404);
        abort_if($opportunity->nomination_deadline && $opportunity->nomination_deadline->isPast(), 404);

        if (!$request->user()->isApprovedForOpportunities()) {
            return redirect()->route('portal.opportunities')
                ->withErrors(['approval' => 'حسابك ما زال بانتظار اعتماد مدير النظام، ولا يمكنك التقديم على الفرص حتى يتم الاعتماد.']);
        }

        $existingApplication = ApplicationRequest::query()
            ->where('employee_id', $employee->id)
            ->where('opportunity_id', $opportunity->id)
            ->latest('id')
            ->first();

        if ($existingApplication) {
            $messages = [
                'submitted' => 'لديك طلب قائم بالفعل لهذه الفرصة وهو بانتظار المراجعة.',
                'under_review' => 'طلبك لهذه الفرصة ما زال قيد المراجعة.',
                'approved' => 'تمت الموافقة على طلبك لهذه الفرصة وهو ضمن مسار الترشيح.',
                'rejected' => 'تم رفض طلبك لهذه الفرصة مسبقًا، ويمكن للإدارة مراجعة القرار من شاشة الطلبات.',
                'withdrawn' => 'سبق تسجيل طلب منسحب لهذه الفرصة، ويمكن للإدارة إعادة فتحه عند الحاجة.',
            ];

            return redirect()->route('portal.applications')
                ->with('status', $messages[$existingApplication->status] ?? 'يوجد طلب سابق لهذه الفرصة.');
        }

        ApplicationRequest::create([
            'employee_id' => $employee->id,
            'opportunity_id' => $opportunity->id,
            'request_date' => now()->toDateString(),
            'status' => 'submitted',
        ]);

        return redirect()->route('portal.applications')->with('status', 'تم إرسال طلب التقديم بنجاح.');
    }

    public function applications(Request $request)
    {
        $applications = $request->user()->employee
            ->applicationRequests()
            ->with('opportunity')
            ->orderByDesc('id')
            ->paginate(15);

        return view('portal.applications', compact('applications'));
    }

    public function profile(Request $request)
    {
        $user = $request->user()->load('employee');
        $departments = Department::orderBy('name')->get();
        $missions = Mission::orderBy('name')->get();

        return view('portal.profile', compact('user', 'departments', 'missions'));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user()->load('employee');

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $user->id],
            'department_id' => ['nullable', 'exists:departments,id'],
            'mission_id' => ['nullable', 'exists:missions,id'],
            'job_title' => ['nullable', 'string', 'max:120'],
            'job_grade' => ['nullable', 'string', 'max:50'],
            'education_level' => ['nullable', 'string', 'max:80'],
            'specialization' => ['nullable', 'string', 'max:120'],
            'languages' => ['nullable', 'string'],
            'language_level' => ['nullable', 'string', 'max:50'],
            'years_of_service' => ['nullable', 'integer', 'min:0'],
            'work_location' => ['nullable', 'string', 'max:120'],
            'employment_status' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        $user->update([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
        ]);

        $user->employee->update([
            'full_name' => $data['full_name'],
            'department_id' => $data['department_id'] ?? null,
            'mission_id' => $data['mission_id'] ?? null,
            'job_title' => $data['job_title'] ?? null,
            'job_grade' => $data['job_grade'] ?? null,
            'education_level' => $data['education_level'] ?? null,
            'specialization' => $data['specialization'] ?? null,
            'languages' => $data['languages'] ?? null,
            'language_level' => $data['language_level'] ?? null,
            'years_of_service' => $data['years_of_service'] ?? null,
            'work_location' => $data['work_location'] ?? null,
            'employment_status' => $data['employment_status'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('portal.profile')->with('status', 'تم تحديث بياناتك بنجاح.');
    }

    public function trainingHistory(Request $request)
    {
        $employee = $request->user()->employee()->with([
            'trainingHistory' => fn ($query) => $query->with('opportunity')->orderByDesc('completion_date')->orderByDesc('id'),
            'applicationRequests' => fn ($query) => $query->with('opportunity')->orderByDesc('id'),
        ])->firstOrFail();

        return view('portal.training-history', compact('employee'));
    }

    public function password()
    {
        return view('portal.password');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'كلمة المرور الحالية غير صحيحة.',
            ]);
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('portal.password')->with('status', 'تم تغيير كلمة المرور بنجاح.');
    }
}
