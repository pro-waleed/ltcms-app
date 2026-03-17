<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('parent')->orderBy('name')->paginate(25);

        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        $parents = Department::orderBy('name')->get();

        return view('departments.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:departments,name'],
            'parent_id' => ['nullable', 'exists:departments,id'],
        ]);

        Department::create($data);

        return redirect()->route('departments.index')->with('status', 'تم حذف الإدارة');
    }

    public function edit(Department $department)
    {
        $parents = Department::where('id', '!=', $department->id)->orderBy('name')->get();

        return view('departments.edit', compact('department', 'parents'));
    }

    public function update(Request $request, Department $department)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:departments,name,' . $department->id],
            'parent_id' => ['nullable', 'exists:departments,id'],
        ]);

        $department->update($data);

        return redirect()->route('departments.index')->with('status', 'تم حذف الإدارة');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('departments.index')->with('status', 'تم حذف الإدارة');
    }
}
