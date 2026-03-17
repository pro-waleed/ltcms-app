@extends('layouts.app')

@section('title', 'الترشيحات')

@section('content')
    <div class="card">
        <h3>إدارة الترشيحات</h3>
        <p class="muted">عرض جميع الترشيحات وحالاتها.</p>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم الترشيح</th>
                    <th>الموظف</th>
                    <th>الفرصة</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>NOM-2026-001</td>
                    <td>سارة علي</td>
                    <td>برنامج القيادة المؤسسية</td>
                    <td><span class="badge">قيد المراجعة</span></td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
