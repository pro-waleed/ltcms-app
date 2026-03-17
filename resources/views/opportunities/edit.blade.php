@extends('layouts.app')

@section('title', 'تعديل فرصة')

@section('content')
    <div class="card">
        <h3>تعديل الفرصة {{ $opportunity->reference_no }}</h3>
        <form method="post" action="{{ route('opportunities.update', $opportunity) }}" class="form">
            @csrf
            @method('PUT')
            <div class="grid grid-2">
                <label>
                    عنوان الفرصة
                    <input type="text" name="title" value="{{ old('title', $opportunity->title) }}">
                </label>
                <label>
                    نوع الفرصة
                    <select name="opportunity_type_id">
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" @selected($type->id === $opportunity->opportunity_type_id)>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    نمط التنفيذ
                    <select name="delivery_mode">
                        <option value="onsite" @selected($opportunity->delivery_mode === 'onsite')>حضوري</option>
                        <option value="online" @selected($opportunity->delivery_mode === 'online')>أونلاين</option>
                        <option value="hybrid" @selected($opportunity->delivery_mode === 'hybrid')>هجين</option>
                    </select>
                </label>
                <label>
                    الشريك
                    <select name="partner_id">
                        <option value="">بدون</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->id }}" @selected($partner->id === $opportunity->partner_id)>{{ $partner->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    اللغة
                    <input type="text" name="language" value="{{ old('language', $opportunity->language) }}">
                </label>
                <label>
                    حالة الفرصة
                    <select name="status">
                        @foreach(['draft' => 'مسودة','received' => 'واردة','under_review' => 'قيد الدراسة','open_for_nomination' => 'مفتوحة للترشيح','closed' => 'مغلقة','nominated' => 'تم الترشيح','executed' => 'منفذة','closed_no_benefit' => 'أغلقت دون استفادة','referred' => 'محالة','cancelled' => 'ملغاة'] as $key => $label)
                            <option value="{{ $key }}" @selected($opportunity->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    تاريخ البداية
                    <input type="date" name="start_date" value="{{ old('start_date', $opportunity->start_date?->format('Y-m-d')) }}">
                </label>
                <label>
                    تاريخ النهاية
                    <input type="date" name="end_date" value="{{ old('end_date', $opportunity->end_date?->format('Y-m-d')) }}">
                </label>
                <label>
                    آخر موعد للترشيح
                    <input type="date" name="nomination_deadline" value="{{ old('nomination_deadline', $opportunity->nomination_deadline?->format('Y-m-d')) }}">
                </label>
                <label>
                    الفئة المستهدفة
                    <input type="text" name="target_group" value="{{ old('target_group', $opportunity->target_group) }}">
                </label>
            </div>
            <label>
                وصف مختصر
                <textarea name="summary" rows="4">{{ old('summary', $opportunity->summary) }}</textarea>
            </label>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">تحديث</button>
                <a class="link" href="{{ route('opportunities.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection
