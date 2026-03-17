@extends('layouts.app')

@section('title', 'الفرص التدريبية')

@section('content')
    <div class="card">
        <h3>إدارة الفرص التدريبية</h3>
        <p class="muted">هذه الصفحة ستعرض كل الفرص مع البحث والتصفية.</p>
        <table class="table">
            <thead>
                <tr>
                    <th>الرقم المرجعي</th>
                    <th>العنوان</th>
                    <th>النمط</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>TR-2026-001</td>
                    <td>ورشة التفاوض الدولي</td>
                    <td>حضوري</td>
                    <td><span class="badge">مفتوحة</span></td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
