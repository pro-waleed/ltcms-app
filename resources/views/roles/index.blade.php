@extends('layouts.app')

@section('title', 'الأدوار')

@section('content')
    <div class="card" style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">إدارة الأدوار</h3>
                <p class="muted">إنشاء الأدوار وتعديلها.</p>
            </div>
            <a class="btn" href="{{ route('roles.create') }}">إضافة دور</a>
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
                    <th>الوصف</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->description }}</td>
                        <td style="white-space: nowrap;">
                            <a class="link" href="{{ route('roles.edit', $role) }}">تعديل</a>
                            <form action="{{ route('roles.destroy', $role) }}" method="post" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="link danger" type="submit">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="muted">لا توجد أدوار.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 12px;">{{ $roles->links() }}</div>
    </div>
@endsection
