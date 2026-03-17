@extends('layouts.app')

@section('title', 'تعديل إدارة')

@section('content')
    <div class="card">
        <h3>تعديل الإدارة</h3>
        <form method="post" action="{{ route('departments.update', $department) }}" class="form">
            @csrf
            @method('PUT')
            <div class="grid grid-2">
                <label>
                    اسم الإدارة
                    <input type="text" name="name" value="{{ old('name', $department->name) }}">
                </label>
                <label>
                    الإدارة الأم
                    <select name="parent_id">
                        <option value="">بدون</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" @selected($parent->id === $department->parent_id)>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">تحديث</button>
                <a class="link" href="{{ route('departments.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection
