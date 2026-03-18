@extends('layouts.app')

@section('title', 'محضر اعتماد الفرصة')

@section('content')
    <div class="card" style="margin-bottom: 16px;">
        <div class="inline-actions" style="justify-content: space-between;">
            <div>
                <h3>محضر اعتماد الفرصة: {{ $opportunity->title }}</h3>
                <p class="muted">رقم الفرصة: {{ $opportunity->reference_no }}</p>
            </div>
            <a class="btn" href="{{ route('reports.opportunity.decision.print', $opportunity) }}">طباعة المحضر</a>
        </div>
    </div>

    <div class="grid grid-4" style="margin-bottom: 16px;">
        <div class="card">
            <h3>المقاعد</h3>
            <div class="kpi">{{ $decision['seats'] ?: '-' }}</div>
        </div>
        <div class="card">
            <h3>أساسي</h3>
            <div class="kpi">{{ $decision['primary_count'] }}</div>
        </div>
        <div class="card">
            <h3>احتياطي</h3>
            <div class="kpi">{{ $decision['reserve_count'] }}</div>
        </div>
        <div class="card">
            <h3>قيد المراجعة</h3>
            <div class="kpi">{{ $decision['pending_count'] }}</div>
        </div>
    </div>

    @if($decision['approved_unassigned_count'] > 0)
        <div class="card" style="margin-bottom: 16px; border-color: #fcd34d; background: #fffaf0;">
            <h3 style="color: #92400e;">تنبيه تكاملي</h3>
            <p class="muted" style="margin: 0; line-height: 1.9;">
                يوجد <strong>{{ $decision['approved_unassigned_count'] }}</strong> طلب/طلبات مقبولة لكن لم تُصنف بعد كأساسي أو احتياطي.
            </p>
        </div>
    @endif

    <div class="card" style="margin-bottom: 16px;">
        <h3>خلاصة المحضر</h3>
        <p class="muted" style="line-height: 1.9; margin: 0;">
            تم تحديد <strong>{{ $decision['primary_count'] }}</strong> مرشحًا أساسيًا و<strong>{{ $decision['reserve_count'] }}</strong> مرشحًا احتياطيًا.
            @if($decision['seats'] > 0 && $decision['seat_gap'] > 0)
                لا تزال هناك <strong>{{ $decision['seat_gap'] }}</strong> مقاعد تحتاج إلى استكمال.
            @elseif($decision['seats'] > 0 && $decision['seat_surplus'] > 0)
                يوجد فائض في المرشحين الأساسيين بمقدار <strong>{{ $decision['seat_surplus'] }}</strong> عن المقاعد المتاحة.
            @else
                التوزيع الحالي متوافق مع المقاعد المحددة أو لم يتم تحديد عدد المقاعد.
            @endif
        </p>
    </div>

    <div class="grid grid-2" style="margin-bottom: 16px;">
        <div class="card">
            <h3>المرشحون الأساسيون</h3>
            @if($decision['primary']->isEmpty())
                <div class="empty">لا يوجد مرشحون أساسيون بعد.</div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>الترتيب</th>
                            <th>الموظف</th>
                            <th>الإدارة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($decision['primary'] as $application)
                            <tr>
                                <td>{{ $application->nomination?->rank_order ?? '-' }}</td>
                                <td>{{ optional($application->employee)->full_name }}</td>
                                <td>{{ optional(optional($application->employee)->department)->name ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="card">
            <h3>المرشحون الاحتياط</h3>
            @if($decision['reserve']->isEmpty())
                <div class="empty">لا يوجد مرشحون احتياط حتى الآن.</div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>الترتيب</th>
                            <th>الموظف</th>
                            <th>الإدارة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($decision['reserve'] as $application)
                            <tr>
                                <td>{{ $application->nomination?->rank_order ?? '-' }}</td>
                                <td>{{ optional($application->employee)->full_name }}</td>
                                <td>{{ optional(optional($application->employee)->department)->name ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <h3>المرفوضون أو المنسحبون</h3>
            @if($decision['rejected']->isEmpty())
                <div class="empty">لا يوجد مرفوضون أو منسحبون.</div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>الموظف</th>
                            <th>الحالة</th>
                            <th>السبب</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($decision['rejected'] as $application)
                            <tr>
                                <td>{{ optional($application->employee)->full_name }}</td>
                                <td>{{ \App\Models\ApplicationRequest::statusLabels()[$application->status] ?? $application->status }}</td>
                                <td>{{ $application->decision_reason ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="card">
            <h3>طلبات ما زالت قيد القرار</h3>
            @if($decision['pending']->isEmpty())
                <div class="empty">لا توجد طلبات معلقة حاليًا.</div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>الموظف</th>
                            <th>الحالة</th>
                            <th>ملاحظات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($decision['pending'] as $application)
                            <tr>
                                <td>{{ optional($application->employee)->full_name }}</td>
                                <td>{{ \App\Models\ApplicationRequest::statusLabels()[$application->status] ?? $application->status }}</td>
                                <td>{{ $application->notes ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    @if($decision['approved_unassigned_count'] > 0)
        <div class="card" style="margin-top: 16px;">
            <h3>طلبات مقبولة دون تصنيف نهائي</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>الموظف</th>
                        <th>الإدارة</th>
                        <th>الترتيب الحالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($decision['approved_unassigned'] as $application)
                        <tr>
                            <td>{{ optional($application->employee)->full_name }}</td>
                            <td>{{ optional(optional($application->employee)->department)->name ?? '-' }}</td>
                            <td>{{ $application->nomination?->rank_order ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
