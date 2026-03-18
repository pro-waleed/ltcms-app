@extends('layouts.app')

@section('title', 'طلبات المشاركة')

@section('content')
    <div class="card" style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">طلبات المشاركة في الفرص</h3>
                <p class="muted">تتبع دورة الطلب من التقديم حتى الترشيح النهائي.</p>
            </div>
            <a class="btn" href="{{ route('applications.create') }}">إضافة طلب</a>
        </div>
    </div>

    <div class="card" style="margin-bottom: 16px;">
        <form method="get" action="{{ route('applications.index') }}" class="form">
            <div class="grid grid-2">
                <label>
                    الفرصة
                    <select name="opportunity_id">
                        <option value="">الكل</option>
                        @foreach($opportunities as $opportunity)
                            <option value="{{ $opportunity->id }}" @selected((string) request('opportunity_id') === (string) $opportunity->id)>
                                {{ $opportunity->reference_no }} - {{ $opportunity->title }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label>
                    الحالة
                    <select name="status">
                        <option value="">الكل</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">تصفية</button>
                <a class="link" href="{{ route('applications.index') }}">إعادة ضبط</a>
            </div>
        </form>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>الرقم</th>
                    <th>الموظف</th>
                    <th>الفرصة</th>
                    <th>تاريخ الطلب</th>
                    <th>حالة الطلب</th>
                    <th>حالة الترشيح</th>
                    <th>مبرر القرار</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $application)
                    <tr>
                        <td>{{ $application->id }}</td>
                        <td>{{ optional($application->employee)->full_name }}</td>
                        <td>{{ optional($application->opportunity)->title }}</td>
                        <td>{{ optional($application->request_date)->format('Y-m-d') }}</td>
                        <td><span class="badge">{{ $statuses[$application->status] ?? $application->status }}</span></td>
                        <td>
                            @if($application->nomination)
                                <span class="badge info">{{ \App\Models\Nomination::statusLabels()[$application->nomination->status] ?? $application->nomination->status }}</span>
                            @else
                                <span class="muted">لم يُنشأ بعد</span>
                            @endif
                        </td>
                        <td>{{ $application->decision_reason ?: '-' }}</td>
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
                    <tr><td colspan="8" class="muted">لا توجد طلبات مشاركة.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 12px;">{{ $applications->links() }}</div>
    </div>
@endsection
