@extends('layouts.app')

@section('title', 'بوابة التدريب والتأهيل')

@section('content')
    <section class="hero">
        <div class="hero-panel">
            <div class="badge info" style="margin-bottom: 12px;">منصة موحدة للترشيح والتقديم</div>
            <h1 style="margin: 0 0 12px; font-size: 34px;">ابدأ من هنا: سجل، استعرض الفرص، وقدّم مباشرة</h1>
            <p style="margin: 0 0 18px; line-height: 1.9;">
                تتيح البوابة للموظف إنشاء حسابه، الحصول على اسم المستخدم، الدخول إلى حسابه، متابعة الفرص التدريبية
                المفتوحة، إرسال طلبات التقديم، وتحديث بياناته وسجله التدريبي من مكان واحد.
            </p>
            <div class="inline-actions">
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
        </div>

        <div class="card stack">
            <div>
                <h3>ما الذي يستطيع الموظف إنجازه؟</h3>
                <div class="muted">خدمات أساسية مكتملة من داخل الحساب.</div>
            </div>
            <div class="grid">
                <div class="card" style="box-shadow: none;">
                    <strong>تسجيل ذاتي</strong>
                    <div class="muted">إنشاء حساب جديد مع توليد اسم مستخدم تلقائي من الرقم الوظيفي.</div>
                </div>
                <div class="card" style="box-shadow: none;">
                    <strong>التقديم على الفرص</strong>
                    <div class="muted">استعراض الفرص المفتوحة وإرسال طلب التقديم مباشرة.</div>
                </div>
                <div class="card" style="box-shadow: none;">
                    <strong>إدارة الحساب</strong>
                    <div class="muted">تعديل البيانات الشخصية وتغيير كلمة المرور واستعادتها.</div>
                </div>
                <div class="card" style="box-shadow: none;">
                    <strong>السجل التدريبي</strong>
                    <div class="muted">عرض البرامج السابقة وطلبات المشاركة الحديثة في صفحة واحدة.</div>
                </div>
            </div>
        </div>
    </section>

    <div class="card">
        <div class="inline-actions" style="justify-content: space-between;">
            <div>
                <h2 style="margin-bottom: 6px;">الفرص المتاحة حاليًا</h2>
                <div class="muted">تعرض هذه القائمة أحدث الفرص المفتوحة للتقديم والترشيح.</div>
            </div>
            @auth
                @if(auth()->user()->employee_id)
                    <a class="btn alt" href="{{ route('portal.opportunities') }}">عرض كل الفرص</a>
                @endif
            @endauth
        </div>

        @if($opportunities->isEmpty())
            <div class="empty" style="margin-top: 16px;">لا توجد فرص مفتوحة حاليًا.</div>
        @else
            <div class="grid grid-3" style="margin-top: 18px;">
                @foreach($opportunities as $opportunity)
                    <div class="card" style="box-shadow: none;">
                        <div class="badge">{{ $opportunity->type?->name ?? 'فرصة تدريبية' }}</div>
                        <h3 style="margin-top: 12px;">{{ $opportunity->title }}</h3>
                        <div class="muted" style="line-height: 1.8;">
                            {{ $opportunity->partner?->name ?? $opportunity->provider_entity ?? 'جهة غير محددة' }}
                        </div>
                        <div class="muted" style="margin-top: 8px;">آخر موعد: {{ optional($opportunity->nomination_deadline)->format('Y-m-d') ?? 'غير محدد' }}</div>
                        <div class="muted">المكان: {{ $opportunity->location_country ?: 'غير محدد' }}</div>
                        @if($opportunity->summary)
                            <p style="line-height: 1.8;">{{ \Illuminate\Support\Str::limit($opportunity->summary, 140) }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
