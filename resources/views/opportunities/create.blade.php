@extends('layouts.app')

@section('title', 'إضافة فرصة')

@section('content')
    <div class="card">
        <h3>إضافة فرصة تدريبية</h3>
        @if($types->isEmpty())
            <p class="muted">لا توجد أنواع فرص مسجلة. أضف نوعًا واحدًا على الأقل قبل إنشاء الفرصة.</p>
        @endif
        <form method="post" action="{{ route('opportunities.store') }}" class="form">
            @csrf
            <div class="grid grid-2">
                <label>
                    عنوان الفرصة
                    <input type="text" name="title" value="{{ old('title') }}">
                </label>
                <label>
                    نوع الفرصة
                    <select name="opportunity_type_id">
                        @foreach($types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    نمط التنفيذ
                    <select name="delivery_mode">
                        <option value="onsite">حضوري</option>
                        <option value="online">أونلاين</option>
                        <option value="hybrid">هجين</option>
                    </select>
                </label>
                <label>
                    الشريك
                    <select name="partner_id">
                        <option value="">بدون</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    اللغة
                    <input type="text" name="language" value="{{ old('language') }}">
                </label>
                <label>
                    عدد المقاعد
                    <input type="number" name="seats" min="1" value="{{ old('seats') }}">
                </label>
                <label>
                    حالة الفرصة
                    <select name="status">
                        <option value="draft">مسودة</option>
                        <option value="received">واردة</option>
                        <option value="under_review">قيد الدراسة</option>
                        <option value="open_for_nomination">مفتوحة للترشيح</option>
                        <option value="closed">مغلقة</option>
                    </select>
                </label>
                <label>
                    تاريخ البداية
                    <input type="date" name="start_date" value="{{ old('start_date') }}">
                </label>
                <label>
                    تاريخ النهاية
                    <input type="date" name="end_date" value="{{ old('end_date') }}">
                </label>
                <label>
                    آخر موعد للترشيح
                    <input type="date" name="nomination_deadline" value="{{ old('nomination_deadline') }}">
                </label>
                <label>
                    الفئة المستهدفة
                    <input type="text" name="target_group" value="{{ old('target_group') }}">
                </label>
            </div>
            <label>
                وصف مختصر
                <textarea name="summary" rows="4">{{ old('summary') }}</textarea>
            </label>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">حفظ</button>
                <a class="link" href="{{ route('opportunities.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection
