@extends('layouts.app')

@section('title', 'تعديل دور')

@section('content')
    <div class="card">
        <h3>تعديل دور</h3>
        <form method="post" action="{{ route('roles.update', $role) }}" class="form">
            @csrf
            @method('PUT')
            <div class="grid grid-2">
                <label>
                    اسم الدور
                    <input type="text" name="name" value="{{ old('name', $role->name) }}">
                </label>
                <label>
                    الوصف
                    <input type="text" name="description" value="{{ old('description', $role->description) }}">
                </label>
            </div>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">تحديث</button>
                <a class="link" href="{{ route('roles.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection
