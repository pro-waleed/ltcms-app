@extends('layouts.app')

@section('title', 'المستخدمون')

@section('content')
    <div class="card" style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">إدارة المستخدمين</h3>
                <p class="muted">إنشاء المستخدمين وتحديد أدوارهم.</p>
            </div>
            <a class="btn" href="{{ route('users.create') }}">إضافة مستخدم</a>
        </div>
        @if(session('status'))
            <p class="success">{{ session('status') }}</p>
        @endif
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المستخدم</th>
                    <th>الاسم الكامل</th>
                    <th>الحالة</th>
                    <th>الأدوار</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->is_active ? 'نشط' : 'غير نشط' }}</td>
                        <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                        <td style="white-space: nowrap;">
                            <a class="link" href="{{ route('users.edit', $user) }}">تعديل</a>
                            <form action="{{ route('users.destroy', $user) }}" method="post" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="link danger" type="submit">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted">لا يوجد مستخدمون.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 12px;">{{ $users->links() }}</div>
    </div>
@endsection
