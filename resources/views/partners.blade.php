@extends('layouts.app')

@section('title', 'الشركاء')

@section('content')
    <div class="card">
        <h3>سجل الشركاء</h3>
        <p class="muted">إدارة الجهات الداعمة والشركاء التدريبيين.</p>
        <table class="table">
            <thead>
                <tr>
                    <th>الشريك</th>
                    <th>التصنيف</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>أكاديمية دبلوماسية</td>
                    <td>معهد تدريب</td>
                    <td><span class="badge">نشط</span></td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
