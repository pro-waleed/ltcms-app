@extends('layouts.app')

@section('title', 'الترشيحات')

@section('content')
    <div class="card" style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">إدارة الترشيحات</h3>
                <p class="muted">تسجيل المرشحين، ترتيبهم، وتحديث حالاتهم النهائية.</p>
            </div>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <a class="btn" href="{{ route('nominations.create') }}">إضافة ترشيح</a>
                <a class="btn alt" href="{{ route('nominations.by-opportunity') }}">إدارة حسب الفرصة</a>
                <a class="btn alt" href="{{ route('nominations.import.form') }}">استيراد من نموذج</a>
            </div>
        </div>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>رقم الترشيح</th>
                    <th>الموظف</th>
                    <th>الفرصة</th>
                    <th>الحالة</th>
                    <th>الفئة</th>
                    <th>الترتيب</th>
                    <th>مبرر القرار</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($nominations as $nomination)
                    <tr>
                        <td>{{ $nomination->nomination_no }}</td>
                        <td>{{ optional($nomination->employee)->full_name }}</td>
                        <td>{{ optional($nomination->opportunity)->title }}</td>
                        <td><span class="badge">{{ \App\Models\Nomination::statusLabels()[$nomination->status] ?? $nomination->status }}</span></td>
                        <td>{{ \App\Models\Nomination::selectionLabels()[$nomination->selection_category] ?? '-' }}</td>
                        <td>{{ $nomination->rank_order ?? '-' }}</td>
                        <td>{{ $nomination->nomination_reason ?: '-' }}</td>
                        <td style="white-space: nowrap;">
                            <a class="link" href="{{ route('nominations.edit', $nomination) }}">تعديل</a>
                            <form action="{{ route('nominations.destroy', $nomination) }}" method="post" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="link danger" type="submit">إغلاق</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="muted">لا توجد ترشيحات مسجلة.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 12px;">{{ $nominations->links() }}</div>
    </div>
@endsection
