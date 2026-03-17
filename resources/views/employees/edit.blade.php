@extends('layouts.app')

@section('title', 'تعديل موظف')

@section('content')
    <div class="card">
        <h3>تعديل بيانات الموظف</h3>
        <form method="post" action="{{ route('employees.update', $employee) }}" class="form">
            @csrf
            @method('PUT')
            <div class="grid grid-2">
                <label>
                    الرقم الوظيفي
                    <input type="text" name="employee_no" value="{{ old('employee_no', $employee->employee_no) }}">
                </label>
                <label>
                    الاسم الكامل
                    <input type="text" name="full_name" value="{{ old('full_name', $employee->full_name) }}">
                </label>
                <label>
                    الإدارة
                    <select name="department_id">
                        <option value="">بدون</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected($department->id === $employee->department_id)>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    البعثة
                    <select name="mission_id">
                        <option value="">بدون</option>
                        @foreach($missions as $mission)
                            <option value="{{ $mission->id }}" @selected($mission->id === $employee->mission_id)>{{ $mission->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    المسمى الوظيفي
                    <input type="text" name="job_title" value="{{ old('job_title', $employee->job_title) }}">
                </label>
                <label>
                    الدرجة الوظيفية
                    <input type="text" name="job_grade" value="{{ old('job_grade', $employee->job_grade) }}">
                </label>
                <label>
                    المؤهل العلمي
                    <input type="text" name="education_level" value="{{ old('education_level', $employee->education_level) }}">
                </label>
                <label>
                    التخصص
                    <input type="text" name="specialization" value="{{ old('specialization', $employee->specialization) }}">
                </label>
                <label>
                    اللغات
                    <input type="text" name="languages" value="{{ old('languages', $employee->languages) }}">
                </label>
                <label>
                    مستوى اللغة
                    <input type="text" name="language_level" value="{{ old('language_level', $employee->language_level) }}">
                </label>
                <label>
                    سنوات الخدمة
                    <input type="number" name="years_of_service" value="{{ old('years_of_service', $employee->years_of_service) }}">
                </label>
                <label>
                    موقع العمل
                    <input type="text" name="work_location" value="{{ old('work_location', $employee->work_location) }}">
                </label>
                <label>
                    الحالة الوظيفية
                    <input type="text" name="employment_status" value="{{ old('employment_status', $employee->employment_status) }}">
                </label>
            </div>
            <label>
                ملاحظات
                <textarea name="notes" rows="4">{{ old('notes', $employee->notes) }}</textarea>
            </label>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">تحديث</button>
                <a class="link" href="{{ route('employees.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection
