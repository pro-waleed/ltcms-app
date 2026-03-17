@extends('layouts.app')

@section('title', 'إضافة دور')

@section('content')
    <div class="card">
        <h3>إضافة دور</h3>
        <form method="post" action="{{ route('roles.store') }}" class="form">
            @csrf
            <div class="grid grid-2">
                <label>
                    اسم الدور
                    <input type="text" name="name" value="{{ old('name') }}">
                </label>
                <label>
                    الوصف
                    <input type="text" name="description" value="{{ old('description') }}">
                </label>
            </div>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">حفظ</button>
                <a class="link" href="{{ route('roles.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection
