@extends('layouts.app')

@section('title', 'إدارة المتقدمين حسب الفرصة')

@section('content')
    <div class="card" style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">إدارة المتقدمين حسب الفرصة</h3>
                <p class="muted">اختر فرصة لعرض جميع المتقدمين وتحديث حالتهم ومبررات القرار.</p>
            </div>
            <a class="link" href="{{ route('nominations.index') }}">رجوع إلى الترشيحات</a>
        </div>
        @if(session('status'))
            <p class="success">{{ session('status') }}</p>
        @endif
    </div>

    <div class="card" style="margin-bottom: 16px;">
        <form method="get" action="{{ route('nominations.by-opportunity') }}" class="form">
            <div class="grid grid-2">
                <label>
                    الفرصة التدريبية
                    <select name="opportunity_id">
                        <option value="">اختر الفرصة</option>
                        @foreach($opportunities as $opportunity)
                            <option value="{{ $opportunity->id }}" @selected(optional($selectedOpportunity)->id === $opportunity->id)>
                                {{ $opportunity->reference_no }} - {{ $opportunity->title }}
                            </option>
                        @endforeach
                    </select>
                </label>
            </div>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">عرض المتقدمين</button>
            </div>
        </form>
    </div>

    @if($selectedOpportunity)
        <div class="card">
            <h3>المتقدمون: {{ $selectedOpportunity->title }}</h3>
            <p class="muted">رقم الفرصة: {{ $selectedOpportunity->reference_no }}</p>

            <form method="post" action="{{ route('nominations.by-opportunity.update') }}">
                @csrf
                <input type="hidden" name="opportunity_id" value="{{ $selectedOpportunity->id }}">
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
                                <td>
                                    <select name="status[{{ $nomination->id }}]">
                                        @foreach(['nominated' => 'مرشح','under_review' => 'قيد المراجعة','approved' => 'معتمد','reserve' => 'احتياطي','rejected' => 'مرفوض','declined' => 'معتذر','attended' => 'شارك','not_attended' => 'لم يشارك','completed' => 'مكتمل','closed' => 'مغلق'] as $key => $label)
                                            <option value="{{ $key }}" @selected($nomination->status === $key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <textarea name="nomination_reason[{{ $nomination->id }}]" rows="2">{{ $nomination->nomination_reason }}</textarea>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="muted">لا يوجد متقدمون لهذه الفرصة.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div style="margin-top: 12px;">
                    <button class="btn" type="submit">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    @endif
@endsection
