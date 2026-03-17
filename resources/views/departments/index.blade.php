@extends('layouts.app')

@section('title', 'الإدارات')

@section('content')
    <div class="card" style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">الإدارات والدوائر</h3>
                <p class="muted">إدارة الهيكل الإداري داخل الوزارة.</p>
            </div>
            <a class="btn" href="{{ route('departments.create') }}">إضافة إدارة</a>
        </div>
        @if(session('status'))
            <p class="success">{{ session('status') }}</p>
        @endif
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>الإدارة الأم</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($departments as $department)
                    <tr>
                        <td>{{ $department->name }}</td>
                        <td>{{ optional($department->parent)->name }}</td>
                        <td style="white-space: nowrap;">
                            <a class="link" href="{{ route('departments.edit', $department) }}">تعديل</a>
                            <form action="{{ route('departments.destroy', $department) }}" method="post" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="link danger" type="submit">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="muted">لا توجد إدارات.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 12px;">{{ $departments->links() }}</div>
    </div>
@endsection
