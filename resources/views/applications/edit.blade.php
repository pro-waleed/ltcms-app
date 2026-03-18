@extends('layouts.app')

@section('title', 'تعديل طلب مشاركة')

@section('content')
    <div class="card" style="max-width: 860px; margin: 0 auto;">
        <h3 style="margin-top: 0;">تعديل طلب مشاركة</h3>
        <form class="form" method="post" action="{{ route('applications.update', $application) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-2">
                <label>
                    الموظف
                    <select name="employee_id" required>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" @selected(old('employee_id', $application->employee_id) == $employee->id)>
                                {{ $employee->full_name }} ({{ $employee->employee_no }})
                            </option>
                        @endforeach
                    </select>
                </label>
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
                    تاريخ الطلب
                    <input type="date" name="request_date" value="{{ old('request_date', optional($application->request_date)->format('Y-m-d')) }}">
                </label>
                <label>
                    حالة الطلب
                    <select name="status" required>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" @selected(old('status', $application->status) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            @if($application->nomination)
                <div class="card" style="margin-top: 14px; box-shadow: none;">
                    <strong>الترشيح المرتبط:</strong>
                    <span class="badge info">{{ \App\Models\Nomination::statusLabels()[$application->nomination->status] ?? $application->nomination->status }}</span>
                </div>
            @endif

            <label style="margin-top: 12px;">
                مبرر القرار
                <textarea name="decision_reason" rows="3">{{ old('decision_reason', $application->decision_reason) }}</textarea>
            </label>
            <label style="margin-top: 12px;">
                ملاحظات
                <textarea name="notes" rows="3">{{ old('notes', $application->notes) }}</textarea>
            </label>

            <div style="margin-top: 16px; display: flex; gap: 10px;">
                <button class="btn" type="submit">تحديث</button>
                <a class="link" href="{{ route('applications.index') }}">عودة</a>
            </div>
        </form>
    </div>
@endsection
