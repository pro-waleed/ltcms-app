@extends('layouts.app')

@section('title', 'إدارة المتقدمين حسب الفرصة')

@section('content')
    <div class="card" style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">إدارة المتقدمين حسب الفرصة</h3>
                <p class="muted">اختر فرصة لعرض كل المتقدمين وحالة الطلب والترشيح في شاشة واحدة.</p>
            </div>
            <a class="link" href="{{ route('nominations.index') }}">رجوع إلى الترشيحات</a>
        </div>
    </div>

    <div class="card" style="margin-bottom: 16px;">
        <form method="get" action="{{ route('nominations.by-opportunity') }}" class="form">
            <div class="grid grid-2">
                <label>
                    الفرصة التدريبية
                    <select name="opportunity_id">
                        <option value="">اختر الفرصة</option>
                        @foreach($opportunities as $opportunity)
                            <option value="{{ $opportunity->id }}" @selected(optional($selectedOpportunity)->id === $opportunity->id)>
                                {{ $opportunity->reference_no }} - {{ $opportunity->title }}
                            </option>
                        @endforeach
                    </select>
                </label>
            </div>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">عرض المتقدمين</button>
            </div>
        </form>
    </div>

    @if($selectedOpportunity)
        <div class="card">
            <h3>المتقدمون: {{ $selectedOpportunity->title }}</h3>
            <p class="muted">رقم الفرصة: {{ $selectedOpportunity->reference_no }}</p>

            <form method="post" action="{{ route('nominations.by-opportunity.update') }}">
                @csrf
                <input type="hidden" name="opportunity_id" value="{{ $selectedOpportunity->id }}">
                <table class="table">
                    <thead>
                        <tr>
                            <th>المتقدم</th>
                            <th>الإدارة</th>
                            <th>حالة الطلب</th>
                            <th>حالة الترشيح</th>
                            <th>مبرر القرار</th>
                            <th>ملاحظات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            <tr>
                                <td>{{ optional($application->employee)->full_name }}</td>
                                <td>{{ optional(optional($application->employee)->department)->name ?? '-' }}</td>
                                <td>
                                    <select name="application_status[{{ $application->id }}]">
                                        @foreach($applicationStatuses as $key => $label)
                                            <option value="{{ $key }}" @selected($application->status === $key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    @if($application->nomination)
                                        <span class="badge info">{{ $nominationStatuses[$application->nomination->status] ?? $application->nomination->status }}</span>
                                    @else
                                        <span class="muted">سيُنشأ عند القبول</span>
                                    @endif
                                </td>
                                <td>
                                    <textarea name="decision_reason[{{ $application->id }}]" rows="2">{{ $application->decision_reason }}</textarea>
                                </td>
                                <td>
                                    <textarea name="notes[{{ $application->id }}]" rows="2">{{ $application->notes }}</textarea>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="muted">لا يوجد متقدمون لهذه الفرصة.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div style="margin-top: 12px;">
                    <button class="btn" type="submit">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    @endif
@endsection
