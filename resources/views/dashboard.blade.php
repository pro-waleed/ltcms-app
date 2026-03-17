@extends('layouts.app')

@section('title', 'لوحة المتابعة')

@section('content')
    <div class="grid grid-4">
        <div class="card">
            <h3>إجمالي الفرص</h3>
            <div class="kpi">{{ $stats['opportunities_total'] }}</div>
            <div class="muted">مجموع الفرص المسجلة</div>
        </div>
        <div class="card">
            <h3>الفرص المفتوحة</h3>
            <div class="kpi">{{ $stats['opportunities_open'] }}</div>
            <div class="muted">قيد الترشيح</div>
        </div>
        <div class="card">
            <h3>إجمالي الترشيحات</h3>
            <div class="kpi">{{ $stats['nominations_total'] }}</div>
            <div class="muted">كل الترشيحات</div>
        </div>
        <div class="card">
            <h3>المستفيدون</h3>
            <div class="kpi">{{ $stats['employees_total'] }}</div>
            <div class="muted">الموظفون المسجلون</div>
        </div>
    </div>

    <div class="grid grid-2" style="margin-top: 20px;">
        <div class="card">
            <h3>آخر الفرص التدريبية</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>الفرصة</th>
                        <th>الجهة</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestOpportunities as $opportunity)
                        <tr>
                            <td>{{ $opportunity->title }}</td>
                            <td>{{ optional($opportunity->partner)->name }}</td>
                            <td><span class="badge">{{ $opportunity->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="muted">لا توجد فرص حتى الآن.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card">
            <h3>آخر الترشيحات</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>المرشح</th>
                        <th>الفرصة</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestNominations as $nomination)
                        <tr>
                            <td>{{ optional($nomination->employee)->full_name }}</td>
                            <td>{{ optional($nomination->opportunity)->title }}</td>
                            <td><span class="badge">{{ $nomination->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="muted">لا توجد ترشيحات حتى الآن.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
