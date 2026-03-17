@extends('layouts.app')

@section('title', 'الموظفون')

@section('content')
    <div class="card">
        <h3>سجل الموظفين</h3>
        <p class="muted">تعرض هذه الصفحة ملفات الموظفين وسجل الاستفادة.</p>
        <table class="table">
            <thead>
                <tr>
                    <th>الرقم الوظيفي</th>
                    <th>الاسم</th>
                    <th>الإدارة</th>
                    <th>عدد الفرص السابقة</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>EMP-0001</td>
                    <td>أحمد صالح</td>
                    <td>إدارة التدريب</td>
                    <td>2</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
