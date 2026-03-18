@extends('layouts.app')

@section('title', 'الفرص المتاحة')

@section('content')
    <div class="card">
        @if(!$canApply)
            <div class="error-box" style="margin-bottom: 18px; background: #fffaf0; border-color: #fcd34d; color: #92400e;">
                حسابك بانتظار اعتماد مدير النظام. يمكنك استعراض الفرص الآن، لكن التقديم سيتاح بعد الاعتماد.
            </div>
        @endif

        <div class="inline-actions" style="justify-content: space-between;">
            <div>
                <h2 style="margin-bottom: 6px;">الفرص التدريبية المتاحة</h2>
                <div class="muted">يمكنك التقديم مرة واحدة على كل فرصة مفتوحة.</div>
            </div>
            <a class="btn alt" href="{{ route('portal.applications') }}">عرض طلباتي</a>
        </div>

        @if($opportunities->isEmpty())
            <div class="empty" style="margin-top: 18px;">لا توجد فرص مفتوحة حاليًا.</div>
        @else
            <div class="grid grid-2" style="margin-top: 18px;">
                @foreach($opportunities as $opportunity)
                    <div class="card" style="box-shadow: none;">
                        <div class="inline-actions" style="justify-content: space-between; align-items: flex-start;">
                            <div>
                                <div class="badge">{{ $opportunity->type?->name ?? 'فرصة تدريبية' }}</div>
                                <h3 style="margin-top: 10px;">{{ $opportunity->title }}</h3>
                            </div>
                            <div class="badge info">{{ optional($opportunity->nomination_deadline)->format('Y-m-d') ?? 'غير محدد' }}</div>
                        </div>

                        <div class="muted" style="line-height: 1.9;">
                            الجهة: {{ $opportunity->partner?->name ?? $opportunity->provider_entity ?? 'غير محددة' }}
                        </div>
                        <div class="muted">المكان: {{ $opportunity->location_country ?: 'غير محدد' }}</div>
                        <div class="muted">اللغة: {{ $opportunity->language ?: 'غير محددة' }}</div>

                        @if($opportunity->summary)
                            <p style="line-height: 1.9; margin: 14px 0;">{{ $opportunity->summary }}</p>
                        @endif

                        @if(in_array($opportunity->id, $appliedIds, true))
                            <div class="badge success">تم التقديم مسبقًا</div>
                        @elseif(!$canApply)
                            <button class="btn" type="button" disabled>بانتظار اعتماد الحساب</button>
                        @else
                            <form method="post" action="{{ route('portal.opportunities.apply', $opportunity) }}" style="margin-top: 14px;">
                                @csrf
                                <button class="btn" type="submit">تقديم على الفرصة</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 18px;">
                {{ $opportunities->links() }}
            </div>
        @endif
    </div>
@endsection
