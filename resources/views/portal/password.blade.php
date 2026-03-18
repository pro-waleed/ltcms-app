@extends('layouts.app')

@section('title', 'تغيير كلمة المرور')

@section('content')
    <div class="card" style="max-width: 640px;">
        <h2>تغيير كلمة المرور</h2>
        <p class="muted">أدخل كلمة المرور الحالية ثم كلمة المرور الجديدة.</p>

        <form method="post" action="{{ route('portal.password.update') }}" class="form" style="margin-top: 18px;">
            @csrf
            @method('PUT')

            <label>
                كلمة المرور الحالية
                <input type="password" name="current_password" required>
            </label>
            <label>
                كلمة المرور الجديدة
                <input type="password" name="password" required>
            </label>
            <label>
                تأكيد كلمة المرور الجديدة
                <input type="password" name="password_confirmation" required>
            </label>

            <div class="inline-actions" style="margin-top: 18px;">
                <button class="btn" type="submit">حفظ كلمة المرور</button>
                <a class="btn alt" href="{{ route('password.request') }}">استعادة كلمة المرور</a>
            </div>
        </form>
    </div>
@endsection
