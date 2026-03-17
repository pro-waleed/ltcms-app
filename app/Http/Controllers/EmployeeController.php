<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Mission;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['department', 'mission'])
            ->orderByDesc('id')
            ->paginate(25);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $missions = Mission::orderBy('name')->get();

        return view('employees.create', compact('departments', 'missions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_no' => ['required', 'string', 'max:50', 'unique:employees,employee_no'],
            'full_name' => ['required', 'string', 'max:150'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'mission_id' => ['nullable', 'exists:missions,id'],
            'job_title' => ['nullable', 'string', 'max:120'],
            'job_grade' => ['nullable', 'string', 'max:50'],
            'education_level' => ['nullable', 'string', 'max:80'],
            'specialization' => ['nullable', 'string', 'max:120'],
            'languages' => ['nullable', 'string'],
            'language_level' => ['nullable', 'string', 'max:50'],
            'years_of_service' => ['nullable', 'integer'],
            'work_location' => ['nullable', 'string', 'max:120'],
            'employment_status' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        Employee::create($data);

        return redirect()->route('employees.index')
            ->with('status', 'تمت إضافة الموظف بنجاح.');
    }

    public function edit(Employee $employee)
    {
        $departments = Department::orderBy('name')->get();
        $missions = Mission::orderBy('name')->get();

        return view('employees.edit', compact('employee', 'departments', 'missions'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'employee_no' => ['required', 'string', 'max:50', 'unique:employees,employee_no,' . $employee->id],
            'full_name' => ['required', 'string', 'max:150'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'mission_id' => ['nullable', 'exists:missions,id'],
            'job_title' => ['nullable', 'string', 'max:120'],
            'job_grade' => ['nullable', 'string', 'max:50'],
            'education_level' => ['nullable', 'string', 'max:80'],
            'specialization' => ['nullable', 'string', 'max:120'],
            'languages' => ['nullable', 'string'],
            'language_level' => ['nullable', 'string', 'max:50'],
            'years_of_service' => ['nullable', 'integer'],
            'work_location' => ['nullable', 'string', 'max:120'],
            'employment_status' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        $employee->update($data);

        return redirect()->route('employees.index')
            ->with('status', 'تم تحديث بيانات الموظف بنجاح.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('status', 'تم حذف الموظف.');
    }
}
