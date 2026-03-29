<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeRegistrationController extends Controller
{
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $missions = Mission::orderBy('name')->get();

        return view('auth.register', compact('departments', 'missions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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

        $credentials = DB::transaction(function () use ($data) {
            $employeeNo = Employee::nextEmployeeNumber();

            $employee = Employee::create([
                'employee_no' => $employeeNo,
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

            User::create([
                'employee_id' => $employee->id,
                'username' => $employeeNo,
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'is_active' => true,
                'approval_status' => 'pending',
            ]);

            return [
                'username' => $employeeNo,
            ];
        });

        return redirect()->route('login')
            ->with('registration_credentials', $credentials)
            ->with('status', 'تم إنشاء الحساب بنجاح، وهو الآن بانتظار اعتماد مدير النظام قبل التقديم على الفرص.');
    }
}
