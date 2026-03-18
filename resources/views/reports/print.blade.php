<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>تقرير الفرص التدريبية</title>
    <style>
        body { font-family: "Cairo", Tahoma, sans-serif; color: #111; }
        h1 { font-size: 20px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
    @php
        $opportunityStatusLabels = \App\Models\Opportunity::statusLabels();
        $deliveryModeLabels = \App\Models\Opportunity::deliveryModeLabels();
    @endphp

    <h1>تقرير الفرص التدريبية</h1>
    <table>
        <thead>
            <tr>
                <th>الرقم المرجعي</th>
                <th>العنوان</th>
                <th>النمط</th>
                <th>الحالة</th>
                <th>البداية</th>
                <th>النهاية</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td>{{ $row->reference_no }}</td>
                    <td>{{ $row->title }}</td>
                    <td>{{ $deliveryModeLabels[$row->delivery_mode] ?? $row->delivery_mode }}</td>
                    <td>{{ $opportunityStatusLabels[$row->status] ?? $row->status }}</td>
                    <td>{{ $row->start_date }}</td>
                    <td>{{ $row->end_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
