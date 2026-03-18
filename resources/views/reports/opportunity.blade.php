@extends('layouts.app')

@section('title', 'تقرير الفرصة')

@section('content')
    <div class="card" style="margin-bottom: 16px;">
        <h3>تقرير الفرصة: {{ $opportunity->title }}</h3>
        <p class="muted">رقم الفرصة: {{ $opportunity->reference_no }}</p>
        <div class="inline-actions" style="margin-top: 12px;">
            <a class="btn" href="{{ route('reports.opportunity.print', $opportunity) }}?reasons=1">طباعة مع المبررات</a>
            <a class="btn alt" href="{{ route('reports.opportunity.print', $opportunity) }}?reasons=0">طباعة بدون المبررات</a>
        </div>
    </div>

    <div class="grid grid-4" style="margin-bottom: 16px;">
        <div class="card">
            <h3>إجمالي الطلبات</h3>
            <div class="kpi">{{ $summary['applications_total'] }}</div>
        </div>
        <div class="card">
            <h3>طلبات قيد المتابعة</h3>
            <div class="kpi">{{ $summary['applications_pending'] }}</div>
        </div>
        <div class="card">
            <h3>طلبات مقبولة</h3>
            <div class="kpi">{{ $summary['applications_approved'] }}</div>
        </div>
        <div class="card">
            <h3>ترشيحات منشأة</h3>
            <div class="kpi">{{ $summary['nominations_total'] }}</div>
        </div>
    </div>

    <div class="grid grid-4" style="margin-bottom: 16px;">
        <div class="card">
            <h3>المقاعد المتاحة</h3>
            <div class="kpi">{{ $summary['seats'] ?: '-' }}</div>
        </div>
        <div class="card">
            <h3>المرشحون الأساسيون</h3>
            <div class="kpi">{{ $summary['primary_count'] }}</div>
        </div>
        <div class="card">
            <h3>المرشحون الاحتياط</h3>
            <div class="kpi">{{ $summary['reserve_count'] }}</div>
        </div>
        <div class="card">
            <h3>فجوة المقاعد</h3>
            <div class="kpi">{{ $summary['seat_gap'] ?: 0 }}</div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 16px;">
        <h3>خلاصة القرار</h3>
        <p class="muted" style="margin: 0; line-height: 1.9;">
            عدد المقاعد المعتمدة: <strong>{{ $summary['seats'] ?: 'غير محدد' }}</strong>،
            المرشحون الأساسيون: <strong>{{ $summary['primary_count'] }}</strong>،
            الاحتياط: <strong>{{ $summary['reserve_count'] }}</strong>،
            المستبعدون أو المرفوضون: <strong>{{ $summary['rejected_count'] }}</strong>.
            @if($summary['seats'] > 0 && $summary['seat_gap'] > 0)
                ما زالت هناك <strong>{{ $summary['seat_gap'] }}</strong> مقاعد غير مغطاة.
            @elseif($summary['seats'] > 0 && $summary['seat_surplus'] > 0)
                يوجد عدد أساسي زائد بمقدار <strong>{{ $summary['seat_surplus'] }}</strong> عن المقاعد المحددة.
            @endif
        </p>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>المتقدم</th>
                    <th>الإدارة</th>
                    <th>حالة الطلب</th>
                    <th>حالة الترشيح</th>
                    <th>فئة الترشيح</th>
                    <th>الترتيب</th>
                    <th>مبرر القرار</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $application)
                    <tr>
                        <td>{{ optional($application->employee)->full_name }}</td>
                        <td>{{ optional(optional($application->employee)->department)->name ?? '-' }}</td>
                        <td><span class="badge">{{ \App\Models\ApplicationRequest::statusLabels()[$application->status] ?? $application->status }}</span></td>
                        <td>
                            @if($application->nomination)
                                <span class="badge info">{{ \App\Models\Nomination::statusLabels()[$application->nomination->status] ?? $application->nomination->status }}</span>
                            @else
                                <span class="muted">غير منشأ</span>
                            @endif
                        </td>
                        <td>
                            @if($application->nomination?->selection_category)
                                <span class="badge success">{{ \App\Models\Nomination::selectionLabels()[$application->nomination->selection_category] ?? $application->nomination->selection_category }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $application->nomination?->rank_order ?? '-' }}</td>
                        <td>{{ $application->decision_reason ?: '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted">لا يوجد متقدمون لهذه الفرصة.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
