@extends('layouts.app')

@section('title', 'لوحة المتابعة')

@section('content')
    @php($opportunityStatusLabels = \App\Models\Opportunity::statusLabels())
    <section class="hero">
        <div class="hero-panel">
            <span class="badge info" style="margin-bottom: 12px;">لوحة متابعة تنفيذية</span>
            <h1 style="margin: 0 0 10px; font-size: 2rem;">صورة سريعة لحالة النظام والقرارات التي تحتاج تدخلًا</h1>
            <p style="margin: 0; line-height: 2;">
                تعرض هذه اللوحة حجم العمل الحالي، حالة الطلبات، حسابات الموظفين بانتظار الاعتماد، والفرص التي تستحق متابعة فورية قبل اعتماد القرار النهائي.
            </p>

            <div class="hero-metrics">
                <div class="metric-chip">
                    <strong>{{ $stats['opportunities_total'] }}</strong>
                    <span>إجمالي الفرص</span>
                </div>
                <div class="metric-chip">
                    <strong>{{ $stats['opportunities_open'] }}</strong>
                    <span>فرص مفتوحة</span>
                </div>
                <div class="metric-chip">
                    <strong>{{ $stats['applications_pending'] }}</strong>
                    <span>طلبات قيد المراجعة</span>
                </div>
                <div class="metric-chip">
                    <strong>{{ $stats['pending_employee_approvals'] }}</strong>
                    <span>اعتمادات موظفين</span>
                </div>
            </div>
        </div>

        <div class="grid grid-2">
            <div class="card stat-card">
                <span class="stat-label">إجمالي الترشيحات</span>
                <div class="kpi">{{ $stats['nominations_total'] }}</div>
                <div class="muted">يشمل كل الترشيحات المسجلة حتى الآن.</div>
            </div>
            <div class="card stat-card">
                <span class="stat-label">الموظفون المسجلون</span>
                <div class="kpi">{{ $stats['employees_total'] }}</div>
                <div class="muted">عدد الموظفين المرتبطين بالنظام.</div>
            </div>
            <div class="card stat-card">
                <span class="stat-label">الحسابات بانتظار الاعتماد</span>
                <div class="kpi">{{ $stats['pending_employee_approvals'] }}</div>
                <div class="muted">ينبغي البت فيها قبل تمكين أصحابها من التقديم.</div>
            </div>
            <div class="card stat-card">
                <span class="stat-label">فرص تحتاج متابعة</span>
                <div class="kpi">{{ $attentionOpportunities->count() }}</div>
                <div class="muted">فرص مفتوحة ما زالت عليها طلبات أو قرارات غير مكتملة.</div>
            </div>
        </div>
    </section>

    <div class="grid grid-2">
        <section class="card">
            <div class="section-head">
                <div>
                    <h3>اعتمادات الموظفين</h3>
                    <div class="muted">طلبات التسجيل الجديدة التي بانتظار قرار مدير النظام.</div>
                </div>
                @if($canManageApprovals)
                    <a class="btn alt" href="{{ route('users.index', ['approval_status' => 'pending']) }}">عرض الكل</a>
                @endif
            </div>

            @if(!$canManageApprovals)
                <div class="empty">عرض التفاصيل الإدارية الكاملة متاح لمدير النظام فقط.</div>
            @elseif($pendingEmployees->isEmpty())
                <div class="empty">لا توجد حسابات موظفين بانتظار الاعتماد حاليًا.</div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>اسم المستخدم</th>
                            <th>الموظف</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingEmployees as $pendingUser)
                            <tr>
                                <td>{{ $pendingUser->username }}</td>
                                <td>{{ $pendingUser->full_name }}</td>
                                <td style="white-space: nowrap;">
                                    @if($canManageApprovals)
                                        <form action="{{ route('users.approve', $pendingUser) }}" method="post" style="display: inline;">
                                            @csrf
                                            <button class="link" type="submit">اعتماد</button>
                                        </form>
                                    @endif
                                    <a class="link" href="{{ route('users.edit', $pendingUser) }}">مراجعة</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>

        <section class="card">
            <div class="section-head">
                <div>
                    <h3>فرص تحتاج متابعة</h3>
                    <div class="muted">متابعة مباشرة للفرص المفتوحة وحالة الطلبات المرتبطة بها.</div>
                </div>
                <a class="btn alt" href="{{ route('reports.index') }}">فتح التقارير</a>
            </div>

            @if($attentionOpportunities->isEmpty())
                <div class="empty">لا توجد فرص مفتوحة تستدعي متابعة خاصة الآن.</div>
            @else
                <div class="surface-list">
                    @foreach($attentionOpportunities as $opportunity)
                        <div class="surface-item">
                            <div class="inline-actions" style="justify-content: space-between; align-items: flex-start;">
                                <div>
                                    <strong>{{ $opportunity->title }}</strong>
                                    <div class="muted">{{ $opportunity->reference_no }}</div>
                                </div>
                                <span class="badge info">{{ optional($opportunity->nomination_deadline)->format('Y-m-d') ?? 'بدون موعد' }}</span>
                            </div>
                            <div class="muted" style="margin-top: 8px; line-height: 1.9;">
                                الطلبات: {{ $opportunity->application_requests_count }} |
                                قيد المراجعة: {{ $opportunity->pending_applications_count }} |
                                المقبولة: {{ $opportunity->approved_applications_count }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    <div class="grid grid-2">
        <section class="card">
            <div class="section-head">
                <div>
                    <h3>أحدث الفرص التدريبية</h3>
                    <div class="muted">آخر الفرص المضافة أو المحدثة في النظام.</div>
                </div>
            </div>
            @if($latestOpportunities->isEmpty())
                <div class="empty">لا توجد فرص حتى الآن.</div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>الفرصة</th>
                            <th>الجهة</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestOpportunities as $opportunity)
                            <tr>
                                <td>{{ $opportunity->title }}</td>
                                <td>{{ optional($opportunity->partner)->name ?? '-' }}</td>
                                <td><span class="badge">{{ $opportunityStatusLabels[$opportunity->status] ?? $opportunity->status }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>

        <section class="card">
            <div class="section-head">
                <div>
                    <h3>أحدث الترشيحات</h3>
                    <div class="muted">نظرة سريعة على آخر الترشيحات التي دخلت مسار المتابعة.</div>
                </div>
            </div>
            @if($latestNominations->isEmpty())
                <div class="empty">لا توجد ترشيحات حتى الآن.</div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>المرشح</th>
                            <th>الفرصة</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestNominations as $nomination)
                            <tr>
                                <td>{{ optional($nomination->employee)->full_name ?? '-' }}</td>
                                <td>{{ optional($nomination->opportunity)->title ?? '-' }}</td>
                                <td><span class="badge">{{ \App\Models\Nomination::statusLabels()[$nomination->status] ?? $nomination->status }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>
    </div>
@endsection
