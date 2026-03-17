@extends('layouts.app')

@section('title', 'إضافة موظف')

@section('content')
    <div class="card">
        <h3>إضافة موظف</h3>
        <form method="post" action="{{ route('employees.store') }}" class="form">
            @csrf
            <div class="grid grid-2">
                <label>
                    الرقم الوظيفي
                    <input type="text" name="employee_no" value="{{ old('employee_no') }}">
                </label>
                <label>
                    الاسم الكامل
                    <input type="text" name="full_name" value="{{ old('full_name') }}">
                </label>
                <label>
                    الإدارة
                    <select name="department_id">
                        <option value="">بدون</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    البعثة
                    <select name="mission_id">
                        <option value="">بدون</option>
                        @foreach($missions as $mission)
                            <option value="{{ $mission->id }}">{{ $mission->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    المسمى الوظيفي
                    <input type="text" name="job_title" value="{{ old('job_title') }}">
                </label>
                <label>
                    الدرجة الوظيفية
                    <input type="text" name="job_grade" value="{{ old('job_grade') }}">
                </label>
                <label>
                    المؤهل العلمي
                    <input type="text" name="education_level" value="{{ old('education_level') }}">
                </label>
                <label>
                    التخصص
                    <input type="text" name="specialization" value="{{ old('specialization') }}">
                </label>
                <label>
                    اللغات
                    <input type="text" name="languages" value="{{ old('languages') }}">
                </label>
                <label>
                    مستوى اللغة
                    <input type="text" name="language_level" value="{{ old('language_level') }}">
                </label>
                <label>
                    سنوات الخدمة
                    <input type="number" name="years_of_service" value="{{ old('years_of_service') }}">
                </label>
                <label>
                    موقع العمل
                    <input type="text" name="work_location" value="{{ old('work_location') }}">
                </label>
                <label>
                    الحالة الوظيفية
                    <input type="text" name="employment_status" value="{{ old('employment_status') }}">
                </label>
            </div>
            <label>
                ملاحظات
                <textarea name="notes" rows="4">{{ old('notes') }}</textarea>
            </label>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">حفظ</button>
                <a class="link" href="{{ route('employees.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection
