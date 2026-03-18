@extends('layouts.app')

@section('title', 'بوابة التدريب والتأهيل')

@section('content')
    @php($deliveryModeLabels = \App\Models\Opportunity::deliveryModeLabels())
    <section class="hero">
        <div class="hero-panel">
            <span class="badge info" style="margin-bottom: 12px;">منصة موحدة لإدارة الترشيح والتقديم</span>
            <h1 style="margin: 0 0 12px; font-size: 2.2rem; line-height: 1.45;">من هنا تبدأ رحلة الموظف من التسجيل حتى التقديم والمتابعة</h1>
            <p style="margin: 0; line-height: 2;">
                تتيح البوابة إنشاء حساب موظف جديد، متابعة اعتماد الحساب من الإدارة، استعراض الفرص التدريبية المفتوحة، إرسال طلبات التقديم، ومراجعة السجل التدريبي من واجهة واحدة واضحة وسريعة.
            </p>

            <div class="inline-actions" style="margin-top: 22px;">
                @guest
                    <a class="btn" href="{{ route('register') }}">تسجيل موظف جديد</a>
                    <a class="btn alt" href="{{ route('login') }}">لدي حساب بالفعل</a>
                @else
                    @if(auth()->user()->employee_id)
                        <a class="btn" href="{{ route('portal.dashboard') }}">الانتقال إلى بوابة الموظف</a>
                    @else
                        <a class="btn" href="{{ route('dashboard') }}">الانتقال إلى لوحة النظام</a>
                    @endif
                @endguest
            </div>

            <div class="hero-metrics">
                <div class="metric-chip">
                    <strong>{{ $heroStats['open_opportunities'] }}</strong>
                    <span>فرصة مفتوحة الآن</span>
                </div>
                <div class="metric-chip">
                    <strong>{{ $heroStats['online_opportunities'] }}</strong>
                    <span>فرصة أونلاين</span>
                </div>
                <div class="metric-chip">
                    <strong>{{ $heroStats['partners_count'] }}</strong>
                    <span>شريك نشط</span>
                </div>
                <div class="metric-chip">
                    <strong>{{ $heroStats['nearest_deadline'] ? \Carbon\Carbon::parse($heroStats['nearest_deadline'])->format('Y-m-d') : '-' }}</strong>
                    <span>أقرب موعد ترشيح</span>
                </div>
            </div>
        </div>

        <div class="card stack">
            <div>
                <h3>ماذا يستطيع الموظف إنجازه؟</h3>
                <div class="muted">رحلة واضحة من أول زيارة حتى متابعة الطلبات.</div>
            </div>

            <div class="surface-list">
                <div class="surface-item">
                    <strong>تسجيل ذاتي منظم</strong>
                    <div class="muted">إنشاء حساب موظف برقم وظيفي تسلسلي مع انتظار الاعتماد الإداري قبل التقديم.</div>
                </div>
                <div class="surface-item">
                    <strong>استعراض الفرص المفتوحة</strong>
                    <div class="muted">عرض الفرص الفعلية المتاحة فقط، مع مراعاة حالة الفرصة وآخر موعد للتقديم.</div>
                </div>
                <div class="surface-item">
                    <strong>متابعة الطلبات والسجل</strong>
                    <div class="muted">مراجعة حالة الطلبات، أسباب القرار، والسجل التدريبي من نفس البوابة.</div>
                </div>
                <div class="surface-item">
                    <strong>إدارة الحساب</strong>
                    <div class="muted">تحديث البيانات الشخصية، تغيير كلمة المرور، واستعادة الوصول عند الحاجة.</div>
                </div>
            </div>
        </div>
    </section>

    <section class="card">
        <div class="section-head">
            <div>
                <h2 style="margin-bottom: 6px;">الفرص المتاحة حاليًا</h2>
                <div class="muted">أحدث الفرص المفتوحة للتقديم والترشيح مع معلومات سريعة تساعد الموظف على اتخاذ القرار.</div>
            </div>
            @auth
                @if(auth()->user()->employee_id)
                    <a class="btn alt" href="{{ route('portal.opportunities') }}">عرض كل الفرص</a>
                @endif
            @endauth
        </div>

        @if($opportunities->isEmpty())
            <div class="empty">لا توجد فرص مفتوحة حاليًا.</div>
        @else
            <div class="grid grid-3">
                @foreach($opportunities as $opportunity)
                    <article class="card soft" style="box-shadow: none;">
                        <div class="inline-actions" style="justify-content: space-between; align-items: flex-start;">
                            <span class="badge">{{ $opportunity->type?->name ?? 'فرصة تدريبية' }}</span>
                            <span class="badge info">{{ optional($opportunity->nomination_deadline)->format('Y-m-d') ?? 'بدون موعد' }}</span>
                        </div>

                        <h3 style="margin-top: 14px;">{{ $opportunity->title }}</h3>
                        <p class="muted" style="line-height: 1.9; margin: 0 0 10px;">
                            الجهة: {{ $opportunity->partner?->name ?? $opportunity->provider_entity ?? 'غير محددة' }}
                        </p>
                        <div class="muted">المكان: {{ $opportunity->location_country ?: 'غير محدد' }}</div>
                        <div class="muted">النمط: {{ $deliveryModeLabels[$opportunity->delivery_mode] ?? $opportunity->delivery_mode }}</div>
                        <div class="muted">المقاعد: {{ $opportunity->seats ?: 'غير محددة' }}</div>

                        @if($opportunity->summary)
                            <p style="line-height: 1.9; margin: 12px 0 0;">
                                {{ \Illuminate\Support\Str::limit($opportunity->summary, 150) }}
                            </p>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
