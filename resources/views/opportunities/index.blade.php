@extends('layouts.app')

@section('title', 'الفرص التدريبية')

@section('content')
    @php
        $opportunityStatusLabels = \App\Models\Opportunity::statusLabels();
        $deliveryModeLabels = \App\Models\Opportunity::deliveryModeLabels();
    @endphp

    <div class="card" style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">إدارة الفرص التدريبية</h3>
                <p class="muted">إضافة وتحديث الفرص التدريبية ومتابعة قرارات الترشيح.</p>
            </div>
            <a class="btn" href="{{ route('opportunities.create') }}">إضافة فرصة جديدة</a>
        </div>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>الرقم المرجعي</th>
                    <th>العنوان</th>
                    <th>النوع</th>
                    <th>النمط</th>
                    <th>المقاعد</th>
                    <th>الحالة</th>
                    <th>التقارير</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($opportunities as $opportunity)
                    <tr>
                        <td>{{ $opportunity->reference_no }}</td>
                        <td>{{ $opportunity->title }}</td>
                        <td>{{ optional($opportunity->type)->name }}</td>
                        <td>{{ $deliveryModeLabels[$opportunity->delivery_mode] ?? $opportunity->delivery_mode }}</td>
                        <td>{{ $opportunity->seats ?: '-' }}</td>
                        <td><span class="badge">{{ $opportunityStatusLabels[$opportunity->status] ?? $opportunity->status }}</span></td>
                        <td style="white-space: nowrap;">
                            <a class="link" href="{{ route('reports.opportunity', $opportunity) }}">تفصيلي</a>
                            <a class="link" href="{{ route('reports.opportunity.decision', $opportunity) }}">محضر</a>
                        </td>
                        <td style="white-space: nowrap;">
                            <a class="link" href="{{ route('opportunities.edit', $opportunity) }}">تعديل</a>
                            <form action="{{ route('opportunities.destroy', $opportunity) }}" method="post" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="link danger" type="submit">إلغاء</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="muted">لا توجد فرص مسجلة.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 12px;">{{ $opportunities->links() }}</div>
    </div>
@endsection
