@extends('layouts.app')

@section('title', 'تقرير الفرصة')

@section('content')
    <div class="card" style="margin-bottom: 16px;">
        <h3>تقرير الفرصة: {{ $opportunity->title }}</h3>
        <p class="muted">رقم الفرصة: {{ $opportunity->reference_no }}</p>
        <div style="margin-top: 12px;">
            <a class="btn" href="{{ route('reports.opportunity.print', $opportunity) }}?reasons=1">طباعة مع المبررات</a>
            <a class="btn" href="{{ route('reports.opportunity.print', $opportunity) }}?reasons=0" style="margin-inline-start: 8px;">طباعة بدون المبررات</a>
        </div>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>المتقدم</th>
                    <th>الحالة</th>
                    <th>مبرر القرار</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nominations as $nomination)
                    <tr>
                        <td>{{ optional($nomination->employee)->full_name }}</td>
                        <td>{{ $nomination->status }}</td>
                        <td>{{ $nomination->nomination_reason }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="muted">لا يوجد متقدمون لهذه الفرصة.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
