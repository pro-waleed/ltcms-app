@extends('layouts.app')

@section('title', 'إضافة إدارة')

@section('content')
    <div class="card">
        <h3>إضافة إدارة</h3>
        <form method="post" action="{{ route('departments.store') }}" class="form">
            @csrf
            <div class="grid grid-2">
                <label>
                    اسم الإدارة
                    <input type="text" name="name" value="{{ old('name') }}">
                </label>
                <label>
                    الإدارة الأم
                    <select name="parent_id">
                        <option value="">بدون</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">حفظ</button>
                <a class="link" href="{{ route('departments.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection
