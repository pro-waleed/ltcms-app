@extends('layouts.app')

@section('title', 'استعادة كلمة المرور')

@section('content')
    <div class="card" style="max-width: 560px; margin: 0 auto;">
        <h2>استعادة كلمة المرور</h2>
        <p class="muted">أدخل بريدك الإلكتروني لإرسال رابط إعادة تعيين كلمة المرور.</p>

        <form method="post" action="{{ route('password.email') }}" class="form" style="margin-top: 18px;">
            @csrf
            <label>
                البريد الإلكتروني
                <input type="email" name="email" value="{{ old('email') }}" required>
            </label>
            <div class="inline-actions" style="margin-top: 18px;">
                <button class="btn" type="submit">إرسال رابط الاستعادة</button>
                <a class="btn alt" href="{{ route('login') }}">العودة إلى الدخول</a>
            </div>
        </form>
    </div>
@endsection
