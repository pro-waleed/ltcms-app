@extends('layouts.app')

@section('title', 'طلبات المشاركة')

@section('content')
    <div class="card" style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">طلبات المشاركة في الدورات</h3>
                <p class="muted">إدارة طلبات التقديم للفرص التدريبية وتتبع حالتها.</p>
            </div>
            <a class="btn" href="{{ route('applications.create') }}">إضافة طلب</a>
        </div>
        @if(session('status'))
            <p class="success">{{ session('status') }}</p>
        @endif
    </div>

    @php
        $statusLabels = [
            'submitted' => 'مقدم',
            'under_review' => 'قيد المراجعة',
            'approved' => 'مقبول',
            'rejected' => 'مرفوض',
            'withdrawn' => 'منسحب',
        ];
    @endphp

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>الرقم</th>
                    <th>الموظف</th>
                    <th>الفرصة</th>
                    <th>تاريخ الطلب</th>
                    <th>الحالة</th>
                    <th>مبرر القرار</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $application)
                    <tr>
                        <td>{{ $application->id }}</td>
                        <td>{{ optional($application->employee)->full_name ?? '—' }}</td>
                        <td>{{ optional($application->opportunity)->title }}</td>
                        <td>{{ optional($application->request_date)->format('Y-m-d') }}</td>
                        <td><span class="badge">{{ $statusLabels[$application->status] ?? $application->status }}</span></td>
                        <td>{{ $application->decision_reason }}</td>
                        <td style="white-space: nowrap;">
                            <a class="link" href="{{ route('applications.edit', $application) }}">تعديل</a>
                            <form action="{{ route('applications.destroy', $application) }}" method="post" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="link danger" type="submit">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted">لا توجد طلبات مشاركة.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 12px;">{{ $applications->links() }}</div>
    </div>
@endsection
