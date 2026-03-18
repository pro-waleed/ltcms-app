@extends('layouts.app')

@section('title', 'الشركاء')

@section('content')
    @php($partnerStatusLabels = \App\Models\Partner::statusLabels())

    <div class="card" style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">سجل الشركاء</h3>
                <p class="muted">إدارة الجهات الداعمة والشركاء التدريبيين.</p>
            </div>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <a class="btn" href="{{ route('partners.create') }}">إضافة شريك</a>
                <a class="btn" href="{{ route('partner-options.index') }}">إدارة قوائم الشركاء</a>
            </div>
        </div>
        @if(session('status'))
            <p class="success">{{ session('status') }}</p>
        @endif
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>الشريك</th>
                    <th>نوع الجهة</th>
                    <th>الدولة</th>
                    <th>المستوى الجغرافي</th>
                    <th>الأهمية الاستراتيجية</th>
                    <th>القطاع</th>
                    <th>الحالة</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($partners as $partner)
                    <tr>
                        <td>{{ $partner->name }}</td>
                        <td>{{ $partner->partner_type }}</td>
                        <td>{{ $partner->country }}</td>
                        <td>{{ $partner->geographic_level }}</td>
                        <td>{{ $partner->strategic_importance }}</td>
                        <td>{{ $partner->sector }}</td>
                        <td><span class="badge">{{ $partnerStatusLabels[$partner->status] ?? $partner->status }}</span></td>
                        <td style="white-space: nowrap;">
                            <a class="link" href="{{ route('partners.edit', $partner) }}">تعديل</a>
                            <form action="{{ route('partners.destroy', $partner) }}" method="post" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="link danger" type="submit">تعطيل</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="muted">لا يوجد شركاء مسجلون.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 12px;">{{ $partners->links() }}</div>
    </div>
@endsection
