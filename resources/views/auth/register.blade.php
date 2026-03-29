@extends('layouts.app')

@section('title', 'تسجيل موظف جديد')

@section('content')
    <div class="card" style="max-width: 960px; margin: 0 auto;">
        <h2>تسجيل موظف جديد</h2>
        <p class="muted">سيتم إنشاء حسابك وربط اسم المستخدم تلقائيًا بالرقم الوظيفي التسلسلي.</p>

        <form id="register-form" method="post" action="{{ route('register.perform') }}" class="form" style="margin-top: 20px;">
            @csrf
            <div class="grid grid-2">
                <label>
                    الاسم الكامل
                    <input type="text" name="full_name" value="{{ old('full_name') }}" required>
                </label>
                <label>
                    البريد الإلكتروني
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </label>
                <label>
                    كلمة المرور
                    <input id="register-password" type="password" name="password" required minlength="8" autocomplete="new-password">
                    <small class="helper-text">8 أحرف على الأقل.</small>
                </label>
                <label>
                    تأكيد كلمة المرور
                    <input id="register-password-confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password">
                    <small id="register-password-hint" class="helper-text" aria-live="polite"></small>
                </label>
                <label>
                    الإدارة
                    <select name="department_id">
                        <option value="">بدون</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    البعثة
                    <select name="mission_id">
                        <option value="">بدون</option>
                        @foreach($missions as $mission)
                            <option value="{{ $mission->id }}" @selected(old('mission_id') == $mission->id)>{{ $mission->name }}</option>
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
                    <input type="number" name="years_of_service" min="0" value="{{ old('years_of_service') }}">
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

            <div class="inline-actions" style="margin-top: 18px;">
                <button class="btn" type="submit">إنشاء الحساب</button>
                <a class="btn alt" href="{{ route('login') }}">لدي حساب بالفعل</a>
            </div>
        </form>
    </div>

    <script>
    (function () {
        const form = document.getElementById('register-form');
        const pass = document.getElementById('register-password');
        const confirm = document.getElementById('register-password-confirmation');
        const hint = document.getElementById('register-password-hint');
        if (!form || !pass || !confirm || !hint) return;

        const clearHint = () => {
            hint.textContent = '';
            hint.className = 'helper-text';
            hint.style.color = '';
        };

        const syncMatch = () => {
            const a = pass.value;
            const b = confirm.value;

            pass.setCustomValidity('');
            confirm.setCustomValidity('');

            if (!b.length) {
                clearHint();
                return;
            }

            if (a !== b) {
                confirm.setCustomValidity('كلمة المرور وتأكيدها غير متطابقتين.');
                hint.textContent = 'كلمة المرور وتأكيدها غير متطابقتين.';
                hint.className = 'helper-text';
                hint.style.color = 'var(--error-text, #8f1d1d)';
                return;
            }

            confirm.setCustomValidity('');
            hint.textContent = 'كلمة المرور متطابقة.';
            hint.className = 'helper-text';
            hint.style.color = 'var(--success-text, #17623f)';
        };

        pass.addEventListener('input', syncMatch);
        confirm.addEventListener('input', syncMatch);

        form.addEventListener('submit', (e) => {
            syncMatch();
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopImmediatePropagation();
                form.reportValidity();
            }
        }, true);
    })();
    </script>
@endsection
