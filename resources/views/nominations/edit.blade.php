@extends('layouts.app')

@section('title', 'تعديل ترشيح')

@section('content')
    <div class="card" style="max-width: 960px; margin: 0 auto;">
        <div class="section-head">
            <div>
                <h3>تعديل الترشيح {{ $nomination->nomination_no }}</h3>
                <div class="muted">تعديل بيانات الترشيح مع إمكانية إعادة اختيار الموظف عبر البحث الذكي.</div>
            </div>
        </div>

        <form method="post" action="{{ route('nominations.update', $nomination) }}" class="form">
            @csrf
            @method('PUT')

            @include('partials.employee_lookup', [
                'lookupId' => 'nomination_employee_edit',
                'fieldName' => 'employee_id',
                'searchName' => 'employee_search',
                'selectedEmployee' => $selectedEmployee,
            ])

            <div class="grid grid-2" style="margin-top: 16px;">
                <label>
                    الفرصة
                    <select name="opportunity_id">
                        @foreach($opportunities as $opportunity)
                            <option value="{{ $opportunity->id }}" @selected(old('opportunity_id', $nomination->opportunity_id) == $opportunity->id)>{{ $opportunity->reference_no }} - {{ $opportunity->title }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    الإدارة المرشحة
                    <select name="nominated_by_department_id">
                        <option value="">بدون</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected(old('nominated_by_department_id', $nomination->nominated_by_department_id) == $department->id)>{{ $department->name }}</option>
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
                            <option value="{{ $key }}" @selected(old('status', $nomination->status) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="grid grid-2" style="margin-top: 12px;">
                <label>
                    مبررات القرار
                    <textarea name="nomination_reason" rows="3">{{ old('nomination_reason', $nomination->nomination_reason) }}</textarea>
                </label>
                <label>
                    ملاحظات
                    <textarea name="notes" rows="4">{{ old('notes', $nomination->notes) }}</textarea>
                </label>
            </div>

            <div class="inline-actions" style="margin-top: 16px;">
                <button class="btn" type="submit">تحديث</button>
                <a class="link" href="{{ route('nominations.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection
