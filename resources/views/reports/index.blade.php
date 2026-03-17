@extends('layouts.app')

@section('title', 'التقارير')

@section('content')
    <div class="grid grid-4" style="margin-bottom: 16px;">
        <div class="card">
            <h3>إجمالي الفرص</h3>
            <div class="kpi">{{ $stats['opportunities_total'] }}</div>
        </div>
        <div class="card">
            <h3>الفرص المفتوحة</h3>
            <div class="kpi">{{ $stats['opportunities_open'] }}</div>
        </div>
        <div class="card">
            <h3>إجمالي الترشيحات</h3>
            <div class="kpi">{{ $stats['nominations_total'] }}</div>
        </div>
        <div class="card">
            <h3>الموظفون</h3>
            <div class="kpi">{{ $stats['employees_total'] }}</div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 16px;">
        <h3>التقارير والمتابعة</h3>
        <p class="muted">تقرير الفرص التدريبية مع التصفية والتصدير.</p>

        <form method="get" action="{{ route('reports.index') }}" class="form">
            <div class="grid grid-2">
                <label>
                    السنة
                    <input type="number" name="year" value="{{ request('year') }}" placeholder="2026">
                </label>
                <label>
                    الحالة
                    <select name="status">
                        <option value="">الكل</option>
                        @foreach(['draft','received','under_review','open_for_nomination','closed','nominated','executed','closed_no_benefit','referred','cancelled'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    نمط التنفيذ
                    <select name="mode">
                        <option value="">الكل</option>
                        <option value="onsite" @selected(request('mode') === 'onsite')>حضوري</option>
                        <option value="online" @selected(request('mode') === 'online')>أونلاين</option>
                        <option value="hybrid" @selected(request('mode') === 'hybrid')>هجين</option>
                    </select>
                </label>
            </div>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">تصفية</button>
                <a class="link" href="{{ route('reports.index') }}">إعادة ضبط</a>
            </div>
        </form>

        <div style="margin-top: 12px;">
            <a class="btn" href="{{ route('reports.export.csv', request()->query()) }}">تصدير CSV (Excel)</a>
            <a class="btn" href="{{ route('reports.print', request()->query()) }}" style="margin-inline-start: 8px;">نسخة للطباعة (PDF)</a>
            <a class="btn" href="{{ route('reports.sync') }}" style="margin-inline-start: 8px;">تحديث السجل التدريبي</a>
        </div>
    </div>

    <div class="grid grid-2" style="margin-bottom: 16px;">
        <div class="card">
            <h3>أفضل 5 شركاء حسب عدد الفرص</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>الشريك</th>
                        <th>عدد الفرص</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($partnersStats as $partner)
                        <tr>
                            <td>{{ $partner->name }}</td>
                            <td>{{ $partner->opportunities_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="muted">لا توجد بيانات.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card">
            <h3>توزيع التمويل</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>نوع التمويل</th>
                        <th>العدد</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fundingStats as $row)
                        <tr>
                            <td>{{ $row->funding_type ?? 'غير محدد' }}</td>
                            <td>{{ $row->total }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="muted">لا توجد بيانات.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-2" style="margin-bottom: 16px;">
        <div class="card">
            <h3>الأكثر استفادة</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>الموظف</th>
                        <th>عدد الترشيحات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fairnessMost as $row)
                        <tr>
                            <td>{{ $row->full_name }}</td>
                            <td>{{ $row->nominations_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="muted">لا توجد بيانات.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card">
            <h3>الأقل استفادة</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>الموظف</th>
                        <th>عدد الترشيحات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fairnessLeast as $row)
                        <tr>
                            <td>{{ $row->full_name }}</td>
                            <td>{{ $row->nominations_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="muted">لا توجد بيانات.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-2" style="margin-bottom: 16px;">
        <div class="card">
            <h3>توزيع الفرص حسب الإدارات</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>الإدارة</th>
                        <th>عدد الترشيحات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departmentsStats as $row)
                        <tr>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->total }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="muted">لا توجد بيانات.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card">
            <h3>توزيع الفرص حسب البعثات</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>البعثة</th>
                        <th>عدد الترشيحات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($missionsStats as $row)
                        <tr>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->total }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="muted">لا توجد بيانات.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>الرقم المرجعي</th>
                    <th>العنوان</th>
                    <th>النمط</th>
                    <th>الحالة</th>
                    <th>البداية</th>
                    <th>النهاية</th>
                    <th>تقرير تفصيلي</th>
                </tr>
            </thead>
            <tbody>
                @forelse($opportunities as $opportunity)
                    <tr>
                        <td>{{ $opportunity->reference_no }}</td>
                        <td>{{ $opportunity->title }}</td>
                        <td>{{ $opportunity->delivery_mode }}</td>
                        <td><span class="badge">{{ $opportunity->status }}</span></td>
                        <td>{{ $opportunity->start_date }}</td>
                        <td>{{ $opportunity->end_date }}</td>
                        <td><a class="link" href="{{ route('reports.opportunity', $opportunity) }}">عرض</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted">لا توجد بيانات للعرض.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 12px;">{{ $opportunities->links() }}</div>
    </div>
@endsection
