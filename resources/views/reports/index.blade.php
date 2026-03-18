@extends('layouts.app')

@section('title', 'التقارير')

@section('content')
    <div class="grid grid-4" style="margin-bottom: 16px;">
        <div class="card">
            <h3>إجمالي الفرص</h3>
            <div class="kpi">{{ $stats['opportunities_total'] }}</div>
        </div>
        <div class="card">
            <h3>الطلبات الكلية</h3>
            <div class="kpi">{{ $stats['applications_total'] }}</div>
        </div>
        <div class="card">
            <h3>طلبات قيد المراجعة</h3>
            <div class="kpi">{{ $stats['applications_pending'] }}</div>
        </div>
        <div class="card">
            <h3>الترشيحات</h3>
            <div class="kpi">{{ $stats['nominations_total'] }}</div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 16px;">
        <h3>التقارير والمتابعة</h3>
        <p class="muted">تصفية فرص التدريب مع مؤشرات الطلبات والترشيحات ومتابعة جاهزية القرار النهائي لكل فرصة.</p>

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

        <div style="margin-top: 12px; display: flex; gap: 8px; flex-wrap: wrap;">
            <a class="btn" href="{{ route('reports.export.csv', request()->query()) }}">تصدير CSV</a>
            <a class="btn alt" href="{{ route('reports.print', request()->query()) }}">نسخة للطباعة</a>
            <form method="post" action="{{ route('reports.sync') }}" style="margin: 0;">
                @csrf
                <button class="btn" type="submit">تحديث السجل التدريبي</button>
            </form>
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
            <h3>توزيع الترشيحات حسب الإدارات</h3>
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
            <h3>توزيع الترشيحات حسب البعثات</h3>
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
                    <th>المقاعد</th>
                    <th>النمط</th>
                    <th>الحالة</th>
                    <th>عدد الطلبات</th>
                    <th>الطلبات المقبولة</th>
                    <th>الترشيحات</th>
                    <th>الأساسي / الاحتياطي</th>
                    <th>جاهزية القرار</th>
                    <th>تقرير تفصيلي</th>
                </tr>
            </thead>
            <tbody>
                @forelse($opportunities as $opportunity)
                    <tr>
                        <td>{{ $opportunity->reference_no }}</td>
                        <td>{{ $opportunity->title }}</td>
                        <td>{{ $opportunity->seats ?: '-' }}</td>
                        <td>{{ $opportunity->delivery_mode }}</td>
                        <td><span class="badge">{{ $opportunity->status }}</span></td>
                        <td>{{ $opportunity->application_requests_count }}</td>
                        <td>{{ $opportunity->approved_applications_count }}</td>
                        <td>{{ $opportunity->nominations_count }}</td>
                        <td>{{ $opportunity->primary_count }} / {{ $opportunity->reserve_count }}</td>
                        <td>
                            <span class="badge {{ $opportunity->decision_state === 'ready' ? 'success' : 'warning' }}">
                                {{ $opportunity->decision_label }}
                            </span>
                        </td>
                        <td><a class="link" href="{{ route('reports.opportunity', $opportunity) }}">عرض</a></td>
                    </tr>
                @empty
                    <tr><td colspan="11" class="muted">لا توجد بيانات للعرض.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 12px;">{{ $opportunities->links() }}</div>
    </div>
@endsection
