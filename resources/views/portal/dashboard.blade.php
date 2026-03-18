@extends('layouts.app')

@section('title', 'بوابة الموظف')

@section('content')
    @if(!$user->isApprovedForOpportunities())
        <div class="card" style="margin-bottom: 18px; border-color: #fcd34d; background: #fffaf0;">
            <h3 style="color: #92400e;">الحساب بانتظار الاعتماد</h3>
            <p class="muted" style="margin: 0;">
                يمكنك استعراض البوابة وتحديث بياناتك، لكن لن تتمكن من التقديم على الفرص التدريبية حتى يعتمد مدير النظام حسابك.
            </p>
        </div>
    @endif

    <div class="hero">
        <div class="hero-panel">
            <div class="badge info" style="margin-bottom: 12px;">مرحبًا {{ $employee->full_name }}</div>
            <h1 style="margin: 0 0 10px; font-size: 32px;">بوابة الموظف</h1>
            <p style="margin: 0 0 18px; line-height: 1.9;">
                من هنا تستطيع متابعة الفرص المفتوحة، إرسال طلبات التقديم، مراجعة سجلك التدريبي، وتحديث بياناتك الشخصية.
            </p>
            <div class="inline-actions">
                <a class="btn" href="{{ route('portal.opportunities') }}">استعراض الفرص</a>
                <a class="btn alt" href="{{ route('portal.profile') }}">تحديث البيانات</a>
            </div>
        </div>

        <div class="grid grid-2">
            <div class="card">
                <div class="muted">الرقم الوظيفي</div>
                <div class="kpi">{{ $employee->employee_no }}</div>
            </div>
            <div class="card">
                <div class="muted">عدد الطلبات</div>
                <div class="kpi">{{ $employee->application_requests_count }}</div>
            </div>
            <div class="card">
                <div class="muted">السجل التدريبي</div>
                <div class="kpi">{{ $employee->training_history_count }}</div>
            </div>
            <div class="card">
                <div class="muted">الإدارة</div>
                <div class="kpi" style="font-size: 18px;">{{ $employee->department?->name ?? 'غير محددة' }}</div>
            </div>
        </div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <h3>أحدث الفرص المفتوحة</h3>
            @if($openOpportunities->isEmpty())
                <div class="empty">لا توجد فرص مفتوحة في الوقت الحالي.</div>
            @else
                <table class="table">
                    <thead>
                    <tr>
                        <th>الفرصة</th>
                        <th>آخر موعد</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($openOpportunities as $opportunity)
                        <tr>
                            <td>{{ $opportunity->title }}</td>
                            <td>{{ optional($opportunity->nomination_deadline)->format('Y-m-d') ?? 'غير محدد' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="card">
            <h3>أحدث طلباتك</h3>
            @if($latestApplications->isEmpty())
                <div class="empty">لم يتم إرسال أي طلب حتى الآن.</div>
            @else
                <table class="table">
                    <thead>
                    <tr>
                        <th>الفرصة</th>
                        <th>الحالة</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($latestApplications as $application)
                        <tr>
                            <td>{{ $application->opportunity?->title ?? 'فرصة محذوفة' }}</td>
                            <td><span class="badge">{{ $application->status }}</span></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
