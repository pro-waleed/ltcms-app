@extends('layouts.app')

@section('title', 'التمويل')

@section('content')
    <div class="card" style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">إدارة التمويل</h3>
                <p class="muted">توثيق عناصر التمويل لكل فرصة.</p>
            </div>
            <a class="btn" href="{{ route('funding.create') }}">إضافة تمويل</a>
        </div>
        @if(session('status'))
            <p class="success">{{ session('status') }}</p>
        @endif
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>نوع التمويل</th>
                    <th>الرسوم</th>
                    <th>الإقامة</th>
                    <th>التذاكر</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($fundings as $funding)
                    <tr>
                        <td>{{ $funding->funding_type }}</td>
                        <td>{{ $funding->training_fees }}</td>
                        <td>{{ $funding->accommodation }}</td>
                        <td>{{ $funding->international_tickets }}</td>
                        <td style="white-space: nowrap;">
                            <a class="link" href="{{ route('funding.edit', $funding) }}">تعديل</a>
                            <form action="{{ route('funding.destroy', $funding) }}" method="post" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="link danger" type="submit">إلغاء</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted">لا توجد سجلات تمويل.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 12px;">{{ $fundings->links() }}</div>
    </div>
@endsection
