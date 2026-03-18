@extends('layouts.app')

@section('title', 'إضافة ترشيح')

@section('content')
    <div class="card" style="max-width: 960px; margin: 0 auto;">
        <div class="section-head">
            <div>
                <h3>إضافة ترشيح</h3>
                <div class="muted">إنشاء ترشيح يدوي مع اختيار الموظف عبر البحث، بما يتناسب مع قاعدة بيانات كبيرة.</div>
            </div>
        </div>

        <form method="post" action="{{ route('nominations.store') }}" class="form">
            @csrf

            @include('partials.employee_lookup', [
                'lookupId' => 'nomination_employee',
                'fieldName' => 'employee_id',
                'searchName' => 'employee_search',
                'selectedEmployee' => $selectedEmployee,
            ])

            <div class="grid grid-2" style="margin-top: 16px;">
                <label>
                    الفرصة
                    <select name="opportunity_id" required>
                        @foreach($opportunities as $opportunity)
                            <option value="{{ $opportunity->id }}" @selected(old('opportunity_id') == $opportunity->id)>{{ $opportunity->reference_no }} - {{ $opportunity->title }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    الإدارة المرشحة
                    <select name="nominated_by_department_id">
                        <option value="">بدون</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected(old('nominated_by_department_id') == $department->id)>{{ $department->name }}</option>
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
                            <option value="{{ $key }}" @selected(old('status', 'nominated') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="grid grid-2" style="margin-top: 12px;">
                <label>
                    مبررات القرار
                    <textarea name="nomination_reason" rows="3">{{ old('nomination_reason') }}</textarea>
                </label>
                <label>
                    ملاحظات
                    <textarea name="notes" rows="4">{{ old('notes') }}</textarea>
                </label>
            </div>

            <div class="inline-actions" style="margin-top: 16px;">
                <button class="btn" type="submit">حفظ</button>
                <a class="link" href="{{ route('nominations.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection
