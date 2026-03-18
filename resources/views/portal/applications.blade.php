@extends('layouts.app')

@section('title', 'طلباتي')

@section('content')
    <div class="card">
        <div class="inline-actions" style="justify-content: space-between;">
            <div>
                <h2 style="margin-bottom: 6px;">طلبات التقديم</h2>
                <div class="muted">كل طلباتك المرسلة على الفرص التدريبية.</div>
            </div>
            <a class="btn alt" href="{{ route('portal.opportunities') }}">العودة إلى الفرص</a>
        </div>

        @if($applications->isEmpty())
            <div class="empty" style="margin-top: 18px;">لا توجد طلبات مقدمة حتى الآن.</div>
        @else
            <table class="table">
                <thead>
                <tr>
                    <th>الفرصة</th>
                    <th>تاريخ الطلب</th>
                    <th>الحالة</th>
                    <th>السبب / الملاحظات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($applications as $application)
                    <tr>
                        <td>{{ $application->opportunity?->title ?? 'فرصة محذوفة' }}</td>
                        <td>{{ optional($application->request_date)->format('Y-m-d') ?? 'غير محدد' }}</td>
                        <td><span class="badge">{{ $application->status }}</span></td>
                        <td>{{ $application->decision_reason ?: ($application->notes ?: '-') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div style="margin-top: 18px;">
                {{ $applications->links() }}
            </div>
        @endif
    </div>
@endsection
