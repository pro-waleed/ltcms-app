@extends('layouts.app')

@section('title', 'البيانات الشخصية')

@section('content')
    <div class="card">
        <h2>البيانات الشخصية</h2>
        <p class="muted">يمكنك تحديث بياناتك الوظيفية والشخصية من هذه الصفحة.</p>

        <form method="post" action="{{ route('portal.profile.update') }}" class="form" style="margin-top: 20px;">
            @csrf
            @method('PUT')

            <div class="grid grid-2">
                <label>
                    اسم المستخدم
                    <input type="text" value="{{ $user->username }}" readonly>
                </label>
                <label>
                    الرقم الوظيفي
                    <input type="text" value="{{ $user->employee?->employee_no }}" readonly>
                </label>
                <label>
                    الاسم الكامل
                    <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required>
                </label>
                <label>
                    البريد الإلكتروني
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </label>
                <label>
                    الإدارة
                    <select name="department_id">
                        <option value="">بدون</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected(old('department_id', $user->employee?->department_id) == $department->id)>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    البعثة
                    <select name="mission_id">
                        <option value="">بدون</option>
                        @foreach($missions as $mission)
                            <option value="{{ $mission->id }}" @selected(old('mission_id', $user->employee?->mission_id) == $mission->id)>{{ $mission->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    المسمى الوظيفي
                    <input type="text" name="job_title" value="{{ old('job_title', $user->employee?->job_title) }}">
                </label>
                <label>
                    الدرجة الوظيفية
                    <input type="text" name="job_grade" value="{{ old('job_grade', $user->employee?->job_grade) }}">
                </label>
                <label>
                    المؤهل العلمي
                    <input type="text" name="education_level" value="{{ old('education_level', $user->employee?->education_level) }}">
                </label>
                <label>
                    التخصص
                    <input type="text" name="specialization" value="{{ old('specialization', $user->employee?->specialization) }}">
                </label>
                <label>
                    اللغات
                    <input type="text" name="languages" value="{{ old('languages', $user->employee?->languages) }}">
                </label>
                <label>
                    مستوى اللغة
                    <input type="text" name="language_level" value="{{ old('language_level', $user->employee?->language_level) }}">
                </label>
                <label>
                    سنوات الخدمة
                    <input type="number" name="years_of_service" min="0" value="{{ old('years_of_service', $user->employee?->years_of_service) }}">
                </label>
                <label>
                    موقع العمل
                    <input type="text" name="work_location" value="{{ old('work_location', $user->employee?->work_location) }}">
                </label>
                <label>
                    الحالة الوظيفية
                    <input type="text" name="employment_status" value="{{ old('employment_status', $user->employee?->employment_status) }}">
                </label>
            </div>

            <label>
                ملاحظات
                <textarea name="notes" rows="4">{{ old('notes', $user->employee?->notes) }}</textarea>
            </label>

            <button class="btn" type="submit">حفظ التعديلات</button>
        </form>
    </div>
@endsection
