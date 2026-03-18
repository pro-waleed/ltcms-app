@extends('layouts.app')

@section('title', 'إعادة تعيين كلمة المرور')

@section('content')
    <div class="card" style="max-width: 560px; margin: 0 auto;">
        <h2>إعادة تعيين كلمة المرور</h2>
        <p class="muted">أدخل البريد الإلكتروني وكلمة المرور الجديدة لإكمال الاستعادة.</p>

        <form method="post" action="{{ route('password.update') }}" class="form" style="margin-top: 18px;">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <label>
                البريد الإلكتروني
                <input type="email" name="email" value="{{ old('email', $email) }}" required>
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
                <button class="btn" type="submit">حفظ كلمة المرور الجديدة</button>
                <a class="btn alt" href="{{ route('login') }}">العودة إلى الدخول</a>
            </div>
        </form>
    </div>
@endsection
