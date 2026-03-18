<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>محضر اعتماد الفرصة</title>
    <style>
        body { font-family: "Cairo", Tahoma, sans-serif; color: #111; }
        h1, h2 { margin-bottom: 8px; }
        p { margin: 0 0 10px; line-height: 1.7; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
        th { background: #f5f5f5; }
        .section { margin-bottom: 18px; }
    </style>
</head>
<body>
    <h1>محضر اعتماد الفرصة</h1>
    <p>الفرصة: {{ $opportunity->title }} ({{ $opportunity->reference_no }})</p>
    <p>عدد المقاعد: {{ $decision['seats'] ?: 'غير محدد' }}</p>
    <p>
        الأساسي: {{ $decision['primary_count'] }} |
        الاحتياطي: {{ $decision['reserve_count'] }} |
        المرفوضون/المنسحبون: {{ $decision['rejected_count'] }} |
        قيد المراجعة: {{ $decision['pending_count'] }}
    </p>

    <div class="section">
        <h2>المرشحون الأساسيون</h2>
        <table>
            <thead>
                <tr>
                    <th>الترتيب</th>
                    <th>الموظف</th>
                    <th>الإدارة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($decision['primary'] as $application)
                    <tr>
                        <td>{{ $application->nomination?->rank_order ?? '-' }}</td>
                        <td>{{ optional($application->employee)->full_name }}</td>
                        <td>{{ optional(optional($application->employee)->department)->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3">لا يوجد مرشحون أساسيون.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>المرشحون الاحتياط</h2>
        <table>
            <thead>
                <tr>
                    <th>الترتيب</th>
                    <th>الموظف</th>
                    <th>الإدارة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($decision['reserve'] as $application)
                    <tr>
                        <td>{{ $application->nomination?->rank_order ?? '-' }}</td>
                        <td>{{ optional($application->employee)->full_name }}</td>
                        <td>{{ optional(optional($application->employee)->department)->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3">لا يوجد مرشحون احتياط.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>المرفوضون أو المنسحبون</h2>
        <table>
            <thead>
                <tr>
                    <th>الموظف</th>
                    <th>الحالة</th>
                    <th>السبب</th>
                </tr>
            </thead>
            <tbody>
                @forelse($decision['rejected'] as $application)
                    <tr>
                        <td>{{ optional($application->employee)->full_name }}</td>
                        <td>{{ \App\Models\ApplicationRequest::statusLabels()[$application->status] ?? $application->status }}</td>
                        <td>{{ $application->decision_reason ?: '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3">لا يوجد مرفوضون أو منسحبون.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
