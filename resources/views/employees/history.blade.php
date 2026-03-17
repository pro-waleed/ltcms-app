@extends('layouts.app')

@section('title', 'السجل التدريبي')

@section('content')
    <div class="card" style="margin-bottom: 16px;">
        <h3>السجل التدريبي: {{ $employee->full_name }}</h3>
        <p class="muted">عرض جميع الفرص التي تقدم لها الموظف وحالة التقديم.</p>
    </div>

    <div class="card" style="margin-bottom: 16px;">
        <h3>طلبات التقديم</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>الفرصة</th>
                    <th>الحالة</th>
                    <th>مبرر القرار</th>
                    <th>تاريخ الترشيح</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employee->nominations as $nomination)
                    <tr>
                        <td>{{ optional($nomination->opportunity)->title }}</td>
                        <td>{{ $nomination->status }}</td>
                        <td>{{ $nomination->nomination_reason }}</td>
                        <td>{{ $nomination->nomination_date }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="muted">لا توجد طلبات حتى الآن.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3>السجل التدريبي (المكتمل)</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>الفرصة</th>
                    <th>الحالة</th>
                    <th>تاريخ الإكمال</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employee->trainingHistory as $record)
                    <tr>
                        <td>{{ optional($record->opportunity)->title }}</td>
                        <td>{{ $record->completion_status }}</td>
                        <td>{{ $record->completion_date }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="muted">لا يوجد سجل تدريبي مكتمل حتى الآن.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 12px;">
            <a class="link" href="{{ route('employees.index') }}">رجوع</a>
        </div>
    </div>
@endsection
