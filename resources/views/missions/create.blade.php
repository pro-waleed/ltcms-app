@extends('layouts.app')

@section('title', 'إضافة بعثة')

@section('content')
    <div class="card">
        <h3>إضافة بعثة</h3>
        <form method="post" action="{{ route('missions.store') }}" class="form">
            @csrf
            <div class="grid grid-2">
                <label>
                    اسم البعثة
                    <input type="text" name="name" value="{{ old('name') }}">
                </label>
                <label>
                    الدولة
                    <input type="text" name="country" value="{{ old('country') }}">
                </label>
                <label>
                    المدينة
                    <input type="text" name="city" value="{{ old('city') }}">
                </label>
            </div>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">حفظ</button>
                <a class="link" href="{{ route('missions.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection
