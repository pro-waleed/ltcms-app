@extends('layouts.app')

@section('title', 'إضافة ترشيح')

@section('content')
    <div class="card">
        <h3>إضافة ترشيح</h3>
        <form method="post" action="{{ route('nominations.store') }}" class="form">
            @csrf
            <div class="grid grid-2">
                <label>
                    الفرصة
                    <select name="opportunity_id">
                        @foreach($opportunities as $opportunity)
                            <option value="{{ $opportunity->id }}">{{ $opportunity->reference_no }} - {{ $opportunity->title }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    الموظف
                    <select name="employee_id">
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    الإدارة المرشحة
                    <select name="nominated_by_department_id">
                        <option value="">بدون</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    تاريخ الترشيح
                    <input type="date" name="nomination_date" value="{{ old('nomination_date') }}">
                </label>
                <label>
                    نوع الترشيح
                    <input type="text" name="nomination_type" value="{{ old('nomination_type') }}">
                </label>
                <label>
                    الحالة
                    <select name="status">
                        @foreach(['nominated' => 'مرشح','under_review' => 'قيد المراجعة','approved' => 'معتمد','reserve' => 'احتياطي','rejected' => 'مرفوض','declined' => 'معتذر','attended' => 'شارك','not_attended' => 'لم يشارك','completed' => 'مكتمل','closed' => 'مغلق'] as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            <label>
                مبررات القرار
                <textarea name="nomination_reason" rows="3">{{ old('nomination_reason') }}</textarea>
            </label>
            <label>
                ملاحظات
                <textarea name="notes" rows="4">{{ old('notes') }}</textarea>
            </label>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">حفظ</button>
                <a class="link" href="{{ route('nominations.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection
