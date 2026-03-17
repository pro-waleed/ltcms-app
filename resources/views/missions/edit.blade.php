@extends('layouts.app')

@section('title', 'تعديل بعثة')

@section('content')
    <div class="card">
        <h3>تعديل بعثة</h3>
        <form method="post" action="{{ route('missions.update', $mission) }}" class="form">
            @csrf
            @method('PUT')
            <div class="grid grid-2">
                <label>
                    اسم البعثة
                    <input type="text" name="name" value="{{ old('name', $mission->name) }}">
                </label>
                <label>
                    الدولة
                    <input type="text" name="country" value="{{ old('country', $mission->country) }}">
                </label>
                <label>
                    المدينة
                    <input type="text" name="city" value="{{ old('city', $mission->city) }}">
                </label>
            </div>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">تحديث</button>
                <a class="link" href="{{ route('missions.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection
