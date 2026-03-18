@extends('layouts.app')

@section('title', 'بوابة الموظف')

@section('content')
    @php($deliveryModeLabels = \App\Models\Opportunity::deliveryModeLabels())
    @if(!$user->isApprovedForOpportunities())
        <div class="panel-note">
            <strong>الحساب بانتظار الاعتماد.</strong>
            يمكنك استعراض بياناتك والفرص المتاحة، لكن التقديم لن يُفعّل حتى يعتمد مدير النظام الحساب.
        </div>
    @endif

    <section class="hero">
        <div class="hero-panel">
            <span class="badge info" style="margin-bottom: 12px;">مرحبًا {{ $employee->full_name }}</span>
            <h1 style="margin: 0 0 10px; font-size: 2rem;">بوابة الموظف التدريبية</h1>
            <p style="margin: 0; line-height: 2;">
                من هنا تستطيع متابعة الفرص المفتوحة، تقديم الطلبات، مراجعة السجل التدريبي، وتحديث بياناتك الشخصية من تجربة واحدة واضحة وسريعة.
            </p>

            <div class="inline-actions" style="margin-top: 22px;">
                <a class="btn" href="{{ route('portal.opportunities') }}">استعراض الفرص</a>
                <a class="btn alt" href="{{ route('portal.profile') }}">تحديث البيانات</a>
                <a class="btn alt" href="{{ route('portal.training-history') }}">السجل التدريبي</a>
            </div>

            <div class="hero-metrics">
                <div class="metric-chip">
                    <strong>{{ $employee->employee_no }}</strong>
                    <span>الرقم الوظيفي</span>
                </div>
                <div class="metric-chip">
                    <strong>{{ $employee->application_requests_count }}</strong>
                    <span>عدد الطلبات</span>
                </div>
                <div class="metric-chip">
                    <strong>{{ $employee->training_history_count }}</strong>
                    <span>برامج في السجل</span>
                </div>
                <div class="metric-chip">
                    <strong style="font-size: 1rem;">{{ $employee->department?->name ?? 'غير محددة' }}</strong>
                    <span>الإدارة</span>
                </div>
            </div>
        </div>

        <div class="card stack">
            <div>
                <h3>ملخص حالتك الحالية</h3>
                <div class="muted">مؤشرات سريعة تساعدك على معرفة ما يجب فعله الآن.</div>
            </div>

            <div class="surface-list">
                <div class="surface-item">
                    <strong>حالة الحساب</strong>
                    <div class="muted">{{ $user->isApprovedForOpportunities() ? 'معتمد ويمكنك التقديم على الفرص.' : 'بانتظار اعتماد مدير النظام.' }}</div>
                </div>
                <div class="surface-item">
                    <strong>عدد الفرص المفتوحة</strong>
                    <div class="muted">توجد {{ $openOpportunities->count() }} فرصة تظهر في البوابة حاليًا.</div>
                </div>
                <div class="surface-item">
                    <strong>آخر طلباتك</strong>
                    <div class="muted">يمكنك تتبع حالة كل طلب من صفحة طلباتي مع أسباب القرار عند توفرها.</div>
                </div>
            </div>
        </div>
    </section>

    <div class="grid grid-2">
        <section class="card">
            <div class="section-head">
                <div>
                    <h3>أحدث الفرص المفتوحة</h3>
                    <div class="muted">الفرص المتاحة حاليًا مع أقرب مواعيد التقديم.</div>
                </div>
                <a class="btn alt" href="{{ route('portal.opportunities') }}">عرض الكل</a>
            </div>

            @if($openOpportunities->isEmpty())
                <div class="empty">لا توجد فرص مفتوحة في الوقت الحالي.</div>
            @else
                <div class="surface-list">
                    @foreach($openOpportunities as $opportunity)
                        <div class="surface-item">
                            <div class="inline-actions" style="justify-content: space-between; align-items: flex-start;">
                                <strong>{{ $opportunity->title }}</strong>
                                <span class="badge info">{{ optional($opportunity->nomination_deadline)->format('Y-m-d') ?? 'بدون موعد' }}</span>
                            </div>
                            <div class="muted" style="margin-top: 6px;">
                                المقاعد: {{ $opportunity->seats ?: 'غير محددة' }} |
                                النمط: {{ $deliveryModeLabels[$opportunity->delivery_mode] ?? $opportunity->delivery_mode }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="card">
            <div class="section-head">
                <div>
                    <h3>أحدث طلباتك</h3>
                    <div class="muted">متابعة مباشرة لحالة الطلبات المرسلة.</div>
                </div>
                <a class="btn alt" href="{{ route('portal.applications') }}">فتح طلباتي</a>
            </div>

            @if($latestApplications->isEmpty())
                <div class="empty">لم يتم إرسال أي طلب حتى الآن.</div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>الفرصة</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestApplications as $application)
                            <tr>
                                <td>{{ $application->opportunity?->title ?? 'فرصة محذوفة' }}</td>
                                <td><span class="badge">{{ $applicationStatuses[$application->status] ?? $application->status }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>
    </div>
@endsection
