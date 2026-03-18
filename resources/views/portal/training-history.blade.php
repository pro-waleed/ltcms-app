@extends('layouts.app')

@section('title', 'السجل التدريبي')

@section('content')
    <div class="grid grid-2">
        <div class="card">
            <h2>السجل التدريبي</h2>
            @if($employee->trainingHistory->isEmpty())
                <div class="empty">لا توجد سجلات تدريبية حتى الآن.</div>
            @else
                <table class="table">
                    <thead>
                    <tr>
                        <th>الفرصة</th>
                        <th>الحالة</th>
                        <th>تاريخ الإكمال</th>
                        <th>الشهادة</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($employee->trainingHistory as $history)
                        <tr>
                            <td>{{ $history->opportunity?->title ?? 'فرصة محذوفة' }}</td>
                            <td><span class="badge">{{ $history->completion_status ?: 'غير محددة' }}</span></td>
                            <td>{{ optional($history->completion_date)->format('Y-m-d') ?? '-' }}</td>
                            <td>{{ $history->certificate_received ? 'تم الاستلام' : 'غير مستلمة' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="card">
            <h2>الطلبات الحديثة</h2>
            @if($employee->applicationRequests->isEmpty())
                <div class="empty">لا توجد طلبات مشاركة حديثة.</div>
            @else
                <table class="table">
                    <thead>
                    <tr>
                        <th>الفرصة</th>
                        <th>الحالة</th>
                        <th>تاريخ الطلب</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($employee->applicationRequests as $application)
                        <tr>
                            <td>{{ $application->opportunity?->title ?? 'فرصة محذوفة' }}</td>
                            <td><span class="badge info">{{ $application->status }}</span></td>
                            <td>{{ optional($application->request_date)->format('Y-m-d') ?? '-' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
