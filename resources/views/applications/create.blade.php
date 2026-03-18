@extends('layouts.app')

@section('title', 'إضافة طلب مشاركة')

@section('content')
    <div class="card" style="max-width: 860px; margin: 0 auto;">
        <h3 style="margin-top: 0;">إضافة طلب مشاركة</h3>
        <form class="form" method="post" action="{{ route('applications.store') }}">
            @csrf
            <div class="grid grid-2">
                <label>
                    الموظف
                    <select name="employee_id" required>
                        <option value="">اختر الموظف</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>
                                {{ $employee->full_name }} ({{ $employee->employee_no }})
                            </option>
                        @endforeach
                    </select>
                </label>
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
                    تاريخ الطلب
                    <input type="date" name="request_date" value="{{ old('request_date') }}">
                </label>
                <label>
                    حالة الطلب
                    <select name="status" required>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" @selected(old('status', 'submitted') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <label style="margin-top: 12px;">
                مبرر القرار
                <textarea name="decision_reason" rows="3">{{ old('decision_reason') }}</textarea>
            </label>
            <label style="margin-top: 12px;">
                ملاحظات
                <textarea name="notes" rows="3">{{ old('notes') }}</textarea>
            </label>

            <div style="margin-top: 16px; display: flex; gap: 10px;">
                <button class="btn" type="submit">حفظ</button>
                <a class="link" href="{{ route('applications.index') }}">عودة</a>
            </div>
        </form>
    </div>
@endsection
