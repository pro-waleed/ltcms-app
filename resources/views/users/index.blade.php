@extends('layouts.app')

@section('title', 'المستخدمون')

@section('content')
    <div class="card" style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">إدارة المستخدمين</h3>
                <p class="muted">إنشاء المستخدمين، إدارة الأدوار، واعتماد حسابات الموظفين.</p>
            </div>
            <a class="btn" href="{{ route('users.create') }}">إضافة مستخدم</a>
        </div>
    </div>

    <div class="card" style="margin-bottom: 16px;">
        <div class="inline-actions">
            <a class="btn {{ request('approval_status') ? 'alt' : '' }}" href="{{ route('users.index') }}">كل المستخدمين</a>
            <a class="btn {{ request('approval_status') === 'pending' ? '' : 'alt' }}" href="{{ route('users.index', ['approval_status' => 'pending']) }}">بانتظار الاعتماد</a>
            <a class="btn {{ request('approval_status') === 'approved' ? '' : 'alt' }}" href="{{ route('users.index', ['approval_status' => 'approved']) }}">الموظفون المعتمدون</a>
        </div>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المستخدم</th>
                    <th>الاسم الكامل</th>
                    <th>نوع الحساب</th>
                    <th>الحالة</th>
                    <th>اعتماد التقديم</th>
                    <th>الأدوار</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->employee_id ? 'موظف' : 'إداري' }}</td>
                        <td>{{ $user->is_active ? 'نشط' : 'غير نشط' }}</td>
                        <td>
                            @if($user->employee_id)
                                @if($user->approval_status === 'approved')
                                    <span class="badge success">معتمد</span>
                                    <div class="muted" style="font-size: 12px; margin-top: 4px;">
                                        بواسطة {{ $user->approver?->full_name ?? 'النظام' }}
                                    </div>
                                    <div class="muted" style="font-size: 12px;">
                                        {{ optional($user->approved_at)->format('Y-m-d H:i') ?? '' }}
                                    </div>
                                @else
                                    <span class="badge">بانتظار الاعتماد</span>
                                @endif
                            @else
                                <span class="muted">غير مطلوب</span>
                            @endif
                        </td>
                        <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                        <td style="white-space: nowrap;">
                            @if($user->employee_id && $user->approval_status !== 'approved')
                                <form action="{{ route('users.approve', $user) }}" method="post" style="display: inline;">
                                    @csrf
                                    <button class="link" type="submit">اعتماد</button>
                                </form>
                            @elseif($user->employee_id)
                                <form action="{{ route('users.mark-pending', $user) }}" method="post" style="display: inline;">
                                    @csrf
                                    <button class="link" type="submit">إرجاع للمراجعة</button>
                                </form>
                            @endif
                            <a class="link" href="{{ route('users.edit', $user) }}">تعديل</a>
                            <form action="{{ route('users.destroy', $user) }}" method="post" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="link danger" type="submit">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted">لا يوجد مستخدمون ضمن هذا التصنيف.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 12px;">{{ $users->links() }}</div>
    </div>
@endsection
