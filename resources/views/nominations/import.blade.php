@extends('layouts.app')

@section('title', 'استيراد الترشيحات')

@section('content')
    <div class="card" style="margin-bottom: 16px;">
        <h3>استيراد الترشيحات من نموذج (CSV)</h3>
        <p class="muted">قم بتصدير الردود من نموذج جوجل بصيغة CSV ثم استوردها هنا.</p>
        @if(session('status'))
            <p class="success">{{ session('status') }}</p>
        @endif
    </div>

    <div class="card" style="margin-bottom: 16px;">
        <h3>قالب الاستيراد</h3>
        <p class="muted">يمكنك تنزيل القالب ثم ملء البيانات مباشرة أو مطابقته مع تصدير جوجل.</p>
        <a class="btn" href="{{ route('nominations.import.template') }}">تنزيل قالب CSV</a>
    </div>

    <div class="card">
        <form method="post" action="{{ route('nominations.import') }}" enctype="multipart/form-data" class="form">
            @csrf
            <label>
                ملف CSV
                <input type="file" name="file" accept=".csv,text/csv">
            </label>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">بدء الاستيراد</button>
                <a class="link" href="{{ route('nominations.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection
