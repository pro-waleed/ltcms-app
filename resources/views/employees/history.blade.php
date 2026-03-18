@extends('layouts.app')

@section('title', 'السجل التدريبي')

@section('content')
    @php($completionStatusLabels = \App\Models\TrainingHistory::completionStatusLabels())

    <div class="card" style="margin-bottom: 16px;">
        <h3>السجل التدريبي: {{ $employee->full_name }}</h3>
        <p class="muted">عرض جميع الفرص التي تقدم لها الموظف وحالة التقديم والتدريب المكتمل.</p>
    </div>

    <div class="card" style="margin-bottom: 16px;">
        <h3>طلبات التقديم والترشيحات</h3>
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
                        <td>{{ \App\Models\Nomination::statusLabels()[$nomination->status] ?? $nomination->status }}</td>
                        <td>{{ $nomination->nomination_reason ?: '-' }}</td>
                        <td>{{ optional($nomination->nomination_date)->format('Y-m-d') ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="muted">لا توجد طلبات حتى الآن.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3>السجل التدريبي المكتمل</h3>
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
                        <td>{{ $completionStatusLabels[$record->completion_status] ?? $record->completion_status }}</td>
                        <td>{{ optional($record->completion_date)->format('Y-m-d') ?? '-' }}</td>
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
