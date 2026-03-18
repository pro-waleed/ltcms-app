@extends('layouts.app')

@section('title', 'إضافة طلب مشاركة')

@section('content')
    <div class="card" style="max-width: 920px; margin: 0 auto;">
        <div class="section-head">
            <div>
                <h3 style="margin-top: 0;">إضافة طلب مشاركة</h3>
                <div class="muted">استخدم البحث لاختيار الموظف بدل القوائم الطويلة، ثم اربط الطلب بالفرصة المناسبة.</div>
            </div>
        </div>

        <form class="form" method="post" action="{{ route('applications.store') }}">
            @csrf

            @include('partials.employee_lookup', [
                'lookupId' => 'application_employee',
                'fieldName' => 'employee_id',
                'searchName' => 'employee_search',
                'selectedEmployee' => $selectedEmployee,
            ])

            <div class="grid grid-2" style="margin-top: 16px;">
                <label>
                    الفرصة التدريبية
                    <select name="opportunity_id" required>
                        <option value="">اختر الفرصة</option>
                        @foreach($opportunities as $opportunity)
                            <option value="{{ $opportunity->id }}" @selected(old('opportunity_id') == $opportunity->id)>
                                {{ $opportunity->reference_no }} - {{ $opportunity->title }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label>
                    حالة الطلب
                    <select name="status" required>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" @selected(old('status', 'submitted') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    تاريخ الطلب
                    <input type="date" name="request_date" value="{{ old('request_date') }}">
                </label>
            </div>

            <div class="grid grid-2" style="margin-top: 12px;">
                <label>
                    مبرر القرار
                    <textarea name="decision_reason" rows="3">{{ old('decision_reason') }}</textarea>
                </label>
                <label>
                    ملاحظات
                    <textarea name="notes" rows="3">{{ old('notes') }}</textarea>
                </label>
            </div>

            <div class="inline-actions" style="margin-top: 18px;">
                <button class="btn" type="submit">حفظ</button>
                <a class="link" href="{{ route('applications.index') }}">عودة</a>
            </div>
        </form>
    </div>
@endsection
