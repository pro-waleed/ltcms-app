@extends('layouts.app')

@section('title', 'إضافة مستخدم')

@section('content')
    <div class="card">
        <h3>إضافة مستخدم</h3>
        <form method="post" action="{{ route('users.store') }}" class="form">
            @csrf
            <div class="grid grid-2">
                <label>
                    اسم المستخدم
                    <input type="text" name="username" value="{{ old('username') }}">
                </label>
                <label>
                    الاسم الكامل
                    <input type="text" name="full_name" value="{{ old('full_name') }}">
                </label>
                <label>
                    البريد الإلكتروني
                    <input type="email" name="email" value="{{ old('email') }}">
                </label>
                <label>
                    كلمة المرور
                    <input type="password" name="password">
                </label>
                <label>
                    الحالة
                    <select name="is_active">
                        <option value="1">نشط</option>
                        <option value="0">غير نشط</option>
                    </select>
                </label>
            </div>
            <label>
                الأدوار
                <div class="grid" style="grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px;">
                    @foreach($roles as $role)
                        <label style="flex-direction: row; align-items: center; gap: 8px;">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}">
                            {{ $role->name }}
                        </label>
                    @endforeach
                </div>
            </label>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">حفظ</button>
                <a class="link" href="{{ route('users.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection
