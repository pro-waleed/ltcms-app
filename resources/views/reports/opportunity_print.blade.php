<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>تقرير تفصيلي للفرصة</title>
    <style>
        body { font-family: "Cairo", Tahoma, sans-serif; color: #111; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        p { margin: 0 0 10px 0; line-height: 1.8; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
        th { background: #f5f5f5; }
        .note { margin: 10px 0; padding: 10px 12px; background: #fff7e7; border: 1px solid #f2d29f; }
    </style>
</head>
<body>
    @php
        $approvedUnassignedCount = $applications->filter(function ($application) {
            return $application->status === 'approved'
                && $application->nomination
                && empty($application->nomination->selection_category);
        })->count();
    @endphp

    <h1>تقرير تفصيلي للفرصة</h1>
    <p>الفرصة: {{ $opportunity->title }} ({{ $opportunity->reference_no }})</p>
    <p>عدد المقاعد: {{ $opportunity->seats ?: 'غير محدد' }}</p>
    <p>طلبات مقبولة دون تصنيف نهائي: {{ $approvedUnassignedCount }}</p>

    @if($approvedUnassignedCount > 0)
        <div class="note">
            يوجد طلبات مقبولة لم تُصنف بعد كأساسي أو احتياطي، لذلك لا يجوز اعتبار القرار النهائي مكتملًا.
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>المتقدم</th>
                <th>حالة الطلب</th>
                <th>حالة الترشيح</th>
                <th>الفئة</th>
                <th>الترتيب</th>
                @if($withReasons)
                    <th>مبرر القرار</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $application)
                <tr>
                    <td>{{ optional($application->employee)->full_name }}</td>
                    <td>{{ \App\Models\ApplicationRequest::statusLabels()[$application->status] ?? $application->status }}</td>
                    <td>{{ $application->nomination ? (\App\Models\Nomination::statusLabels()[$application->nomination->status] ?? $application->nomination->status) : 'غير منشأ' }}</td>
                    <td>
                        @if($application->nomination?->selection_category)
                            {{ \App\Models\Nomination::selectionLabels()[$application->nomination->selection_category] ?? $application->nomination->selection_category }}
                        @else
                            غير مصنف
                        @endif
                    </td>
                    <td>{{ $application->nomination?->rank_order ?? '-' }}</td>
                    @if($withReasons)
                        <td>{{ $application->decision_reason ?: '-' }}</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $withReasons ? 6 : 5 }}">لا يوجد متقدمون لهذه الفرصة.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
