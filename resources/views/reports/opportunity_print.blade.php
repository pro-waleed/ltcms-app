<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>تقرير تفصيلي للفرصة</title>
    <style>
        body { font-family: "Cairo", Tahoma, sans-serif; color: #111; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        p { margin: 0 0 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
    <h1>تقرير تفصيلي للفرصة</h1>
    <p>الفرصة: {{ $opportunity->title }} ({{ $opportunity->reference_no }})</p>
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
                    <td>{{ $application->nomination ? (\App\Models\Nomination::selectionLabels()[$application->nomination->selection_category] ?? '-') : '-' }}</td>
                    <td>{{ $application->nomination?->rank_order ?? '-' }}</td>
                    @if($withReasons)
                        <td>{{ $application->decision_reason }}</td>
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
