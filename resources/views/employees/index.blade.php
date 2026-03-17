@extends('layouts.app')

@section('title', 'الموظفون')

@section('content')
    <div class="card" style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">سجل الموظفين</h3>
                <p class="muted">إدارة بيانات الموظفين وسجل الاستفادة.</p>
            </div>
            <a class="btn" href="{{ route('employees.create') }}">إضافة موظف</a>
        </div>
        @if(session('status'))
            <p class="success">{{ session('status') }}</p>
        @endif
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>الرقم الوظيفي</th>
                    <th>الاسم</th>
                    <th>الإدارة</th>
                    <th>البعثة</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                    <tr>
                        <td>{{ $employee->employee_no }}</td>
                        <td>{{ $employee->full_name }}</td>
                        <td>{{ optional($employee->department)->name }}</td>
                        <td>{{ optional($employee->mission)->name }}</td>
                        <td style="white-space: nowrap;">
                            <a class="link" href="{{ route('employees.edit', $employee) }}">تعديل</a>
                            <a class="link" href="{{ route('employees.history', $employee) }}">سجل تدريبي</a>
                            <form action="{{ route('employees.destroy', $employee) }}" method="post" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="link danger" type="submit">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted">لا يوجد موظفون مسجلون.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 12px;">{{ $employees->links() }}</div>
    </div>
@endsection
