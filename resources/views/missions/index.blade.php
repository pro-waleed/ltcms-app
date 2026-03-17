@extends('layouts.app')

@section('title', 'البعثات')

@section('content')
    <div class="card" style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">البعثات الدبلوماسية</h3>
                <p class="muted">إدارة البعثات والسفارات والقنصليات.</p>
            </div>
            <a class="btn" href="{{ route('missions.create') }}">إضافة بعثة</a>
        </div>
        @if(session('status'))
            <p class="success">{{ session('status') }}</p>
        @endif
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>الدولة</th>
                    <th>المدينة</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($missions as $mission)
                    <tr>
                        <td>{{ $mission->name }}</td>
                        <td>{{ $mission->country }}</td>
                        <td>{{ $mission->city }}</td>
                        <td style="white-space: nowrap;">
                            <a class="link" href="{{ route('missions.edit', $mission) }}">تعديل</a>
                            <form action="{{ route('missions.destroy', $mission) }}" method="post" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="link danger" type="submit">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="muted">لا توجد بعثات.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 12px;">{{ $missions->links() }}</div>
    </div>
@endsection
