@extends('layouts.app')

@section('title', 'طلباتي')

@section('content')
    <section class="card">
        <div class="section-head">
            <div>
                <h2 style="margin-bottom: 6px;">طلبات التقديم</h2>
                <div class="muted">كل الطلبات التي أرسلتها على الفرص التدريبية مع حالة كل طلب وملاحظات القرار.</div>
            </div>
            <a class="btn alt" href="{{ route('portal.opportunities') }}">العودة إلى الفرص</a>
        </div>

        @if($applications->isEmpty())
            <div class="empty">لا توجد طلبات مقدمة حتى الآن.</div>
        @else
            <div class="grid grid-2">
                @foreach($applications as $application)
                    <article class="card soft" style="box-shadow: none;">
                        <div class="inline-actions" style="justify-content: space-between; align-items: flex-start;">
                            <div>
                                <h3 style="margin-bottom: 4px;">{{ $application->opportunity?->title ?? 'فرصة محذوفة' }}</h3>
                                <div class="muted">تاريخ الطلب: {{ optional($application->request_date)->format('Y-m-d') ?? 'غير محدد' }}</div>
                            </div>
                            <span class="badge">{{ $applicationStatuses[$application->status] ?? $application->status }}</span>
                        </div>

                        <div style="margin-top: 14px; line-height: 1.9;">
                            <strong>ملاحظات القرار</strong>
                            <div class="muted">
                                {{ $application->decision_reason ?: ($application->notes ?: 'لا توجد ملاحظات مسجلة حتى الآن.') }}
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div style="margin-top: 18px;">
                {{ $applications->links() }}
            </div>
        @endif
    </section>
@endsection
