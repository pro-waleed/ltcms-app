@extends('layouts.app')

@section('title', 'تعديل طلب مشاركة')

@section('content')
    <div class="card" style="max-width: 920px; margin: 0 auto;">
        <div class="section-head">
            <div>
                <h3 style="margin-top: 0;">تعديل طلب مشاركة</h3>
                <div class="muted">يمكنك تعديل الموظف أو الفرصة أو القرار مع إبقاء الترشيح المرتبط متزامنًا مع حالة الطلب.</div>
            </div>
        </div>

        <form class="form" method="post" action="{{ route('applications.update', $application) }}">
            @csrf
            @method('PUT')

            @include('partials.employee_lookup', [
                'lookupId' => 'application_employee_edit',
                'fieldName' => 'employee_id',
                'searchName' => 'employee_search',
                'selectedEmployee' => $selectedEmployee,
            ])

            <div class="grid grid-2" style="margin-top: 16px;">
                <label>
                    الفرصة التدريبية
                    <select name="opportunity_id" required>
                        @foreach($opportunities as $opportunity)
                            <option value="{{ $opportunity->id }}" @selected(old('opportunity_id', $application->opportunity_id) == $opportunity->id)>
                                {{ $opportunity->reference_no }} - {{ $opportunity->title }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label>
                    حالة الطلب
                    <select name="status" required>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" @selected(old('status', $application->status) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    تاريخ الطلب
                    <input type="date" name="request_date" value="{{ old('request_date', optional($application->request_date)->format('Y-m-d')) }}">
                </label>
            </div>

            @if($application->nomination)
                <div class="card soft" style="margin-top: 14px; box-shadow: none;">
                    <strong>الترشيح المرتبط:</strong>
                    <span class="badge info">{{ \App\Models\Nomination::statusLabels()[$application->nomination->status] ?? $application->nomination->status }}</span>
                </div>
            @endif

            <div class="grid grid-2" style="margin-top: 12px;">
                <label>
                    مبرر القرار
                    <textarea name="decision_reason" rows="3">{{ old('decision_reason', $application->decision_reason) }}</textarea>
                </label>
                <label>
                    ملاحظات
                    <textarea name="notes" rows="3">{{ old('notes', $application->notes) }}</textarea>
                </label>
            </div>

            <div class="inline-actions" style="margin-top: 18px;">
                <button class="btn" type="submit">تحديث</button>
                <a class="link" href="{{ route('applications.index') }}">عودة</a>
            </div>
        </form>
    </div>
@endsection
