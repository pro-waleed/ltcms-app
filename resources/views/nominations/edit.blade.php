@extends('layouts.app')

@section('title', 'تعديل ترشيح')

@section('content')
    <div class="card">
        <h3>تعديل الترشيح {{ $nomination->nomination_no }}</h3>
        <form method="post" action="{{ route('nominations.update', $nomination) }}" class="form">
            @csrf
            @method('PUT')
            <div class="grid grid-2">
                <label>
                    الفرصة
                    <select name="opportunity_id">
                        @foreach($opportunities as $opportunity)
                            <option value="{{ $opportunity->id }}" @selected($opportunity->id === $nomination->opportunity_id)>{{ $opportunity->reference_no }} - {{ $opportunity->title }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    الموظف
                    <select name="employee_id">
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" @selected($employee->id === $nomination->employee_id)>{{ $employee->full_name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    الإدارة المرشحة
                    <select name="nominated_by_department_id">
                        <option value="">بدون</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected($department->id === $nomination->nominated_by_department_id)>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    تاريخ الترشيح
                    <input type="date" name="nomination_date" value="{{ old('nomination_date', $nomination->nomination_date?->format('Y-m-d')) }}">
                </label>
                <label>
                    نوع الترشيح
                    <input type="text" name="nomination_type" value="{{ old('nomination_type', $nomination->nomination_type) }}">
                </label>
                <label>
                    الحالة
                    <select name="status">
                        @foreach(['nominated' => 'مرشح','under_review' => 'قيد المراجعة','approved' => 'معتمد','reserve' => 'احتياطي','rejected' => 'مرفوض','declined' => 'معتذر','attended' => 'شارك','not_attended' => 'لم يشارك','completed' => 'مكتمل','closed' => 'مغلق'] as $key => $label)
                            <option value="{{ $key }}" @selected($nomination->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            <label>
                مبررات القرار
                <textarea name="nomination_reason" rows="3">{{ old('nomination_reason', $nomination->nomination_reason) }}</textarea>
            </label>
            <label>
                ملاحظات
                <textarea name="notes" rows="4">{{ old('notes', $nomination->notes) }}</textarea>
            </label>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">تحديث</button>
                <a class="link" href="{{ route('nominations.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection
