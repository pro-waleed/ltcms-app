@extends('layouts.app')

@section('title', 'إضافة تمويل')

@section('content')
    <div class="card">
        <h3>إضافة تمويل</h3>
        <form method="post" action="{{ route('funding.store') }}" class="form">
            @csrf
            <div class="grid grid-2">
                <label>
                    نوع التمويل
                    <select name="funding_type">
                        <option value="fully_funded">ممول بالكامل</option>
                        <option value="partially_funded">ممول جزئيًا</option>
                        <option value="not_funded">غير ممول</option>
                        <option value="co_funded">تمويل مشترك</option>
                    </select>
                </label>
                <label>
                    ربط بفرصة
                    <select name="opportunity_id">
                        <option value="">بدون</option>
                        @foreach($opportunities as $opportunity)
                            <option value="{{ $opportunity->id }}">{{ $opportunity->reference_no }} - {{ $opportunity->title }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    الرسوم التدريبية
                    <select name="training_fees">
                        <option value="included">مشمول</option>
                        <option value="excluded">غير مشمول</option>
                        <option value="unspecified">غير محدد</option>
                    </select>
                </label>
                <label>
                    التذاكر الدولية
                    <select name="international_tickets">
                        <option value="included">مشمول</option>
                        <option value="excluded">غير مشمول</option>
                        <option value="unspecified">غير محدد</option>
                    </select>
                </label>
                <label>
                    التذاكر الداخلية
                    <select name="domestic_tickets">
                        <option value="included">مشمول</option>
                        <option value="excluded">غير مشمول</option>
                        <option value="unspecified">غير محدد</option>
                    </select>
                </label>
                <label>
                    الإقامة
                    <select name="accommodation">
                        <option value="included">مشمول</option>
                        <option value="excluded">غير مشمول</option>
                        <option value="unspecified">غير محدد</option>
                    </select>
                </label>
                <label>
                    الوجبات
                    <select name="meals">
                        <option value="included">مشمول</option>
                        <option value="excluded">غير مشمول</option>
                        <option value="unspecified">غير محدد</option>
                    </select>
                </label>
                <label>
                    المواصلات المحلية
                    <select name="local_transport">
                        <option value="included">مشمول</option>
                        <option value="excluded">غير مشمول</option>
                        <option value="unspecified">غير محدد</option>
                    </select>
                </label>
                <label>
                    التأمين الصحي
                    <select name="health_insurance">
                        <option value="included">مشمول</option>
                        <option value="excluded">غير مشمول</option>
                        <option value="unspecified">غير محدد</option>
                    </select>
                </label>
                <label>
                    رسوم التأشيرة
                    <select name="visa_fees">
                        <option value="included">مشمول</option>
                        <option value="excluded">غير مشمول</option>
                        <option value="unspecified">غير محدد</option>
                    </select>
                </label>
                <label>
                    بدل يومي
                    <select name="per_diem">
                        <option value="included">مشمول</option>
                        <option value="excluded">غير مشمول</option>
                        <option value="unspecified">غير محدد</option>
                    </select>
                </label>
                <label>
                    مواد تدريبية
                    <select name="training_materials">
                        <option value="included">مشمول</option>
                        <option value="excluded">غير مشمول</option>
                        <option value="unspecified">غير محدد</option>
                    </select>
                </label>
                <label>
                    دعم تقني/إنترنت
                    <select name="tech_support">
                        <option value="included">مشمول</option>
                        <option value="excluded">غير مشمول</option>
                        <option value="unspecified">غير محدد</option>
                    </select>
                </label>
            </div>
            <label>
                التزامات الوزارة
                <textarea name="ministry_obligations" rows="3">{{ old('ministry_obligations') }}</textarea>
            </label>
            <label>
                ملاحظات
                <textarea name="notes" rows="3">{{ old('notes') }}</textarea>
            </label>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">حفظ</button>
                <a class="link" href="{{ route('funding.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection
