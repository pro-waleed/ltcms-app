@extends('layouts.app')

@section('title', 'تعديل شريك')

@section('content')
    <div class="card">
        <h3>تعديل بيانات الشريك</h3>
        <form method="post" action="{{ route('partners.update', $partner) }}" class="form">
            @csrf
            @method('PUT')
            <div class="grid grid-2">
                <label>
                    اسم الشريك
                    <input type="text" name="name" value="{{ old('name', $partner->name) }}">
                </label>
                <label>
                    نوع الجهة
                    <select name="partner_type">
                        @foreach(($options['partner_type'] ?? collect()) as $option)
                            <option value="{{ $option->label }}" @selected(old('partner_type', $partner->partner_type) === $option->label)>{{ $option->label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    المستوى الجغرافي
                    <select name="geographic_level">
                        <option value="">بدون</option>
                        @foreach(($options['geographic_level'] ?? collect()) as $option)
                            <option value="{{ $option->label }}" @selected(old('geographic_level', $partner->geographic_level) === $option->label)>{{ $option->label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    الأهمية الاستراتيجية
                    <select name="strategic_importance">
                        <option value="">بدون</option>
                        @foreach(($options['strategic_importance'] ?? collect()) as $option)
                            <option value="{{ $option->label }}" @selected(old('strategic_importance', $partner->strategic_importance) === $option->label)>{{ $option->label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    القطاع
                    <select name="sector">
                        <option value="">بدون</option>
                        @foreach(($options['sector'] ?? collect()) as $option)
                            <option value="{{ $option->label }}" @selected(old('sector', $partner->sector) === $option->label)>{{ $option->label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    الدولة
                    <input type="text" name="country" value="{{ old('country', $partner->country) }}">
                </label>
                <label>
                    طبيعة الشراكة
                    <select name="partnership_nature">
                        <option value="">بدون</option>
                        @foreach(($options['partnership_nature'] ?? collect()) as $option)
                            <option value="{{ $option->label }}" @selected(old('partnership_nature', $partner->partnership_nature) === $option->label)>{{ $option->label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    نقطة الاتصال
                    <input type="text" name="contact_name" value="{{ old('contact_name', $partner->contact_name) }}">
                </label>
                <label>
                    البريد الإلكتروني
                    <input type="email" name="contact_email" value="{{ old('contact_email', $partner->contact_email) }}">
                </label>
                <label>
                    الهاتف
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $partner->contact_phone) }}">
                </label>
                <label>
                    الحالة
                    <select name="status">
                        <option value="active" @selected($partner->status === 'active')>نشط</option>
                        <option value="inactive" @selected($partner->status === 'inactive')>غير نشط</option>
                    </select>
                </label>
                <label>
                    نوع الفرص المعتادة
                    <input type="text" name="typical_opportunities" value="{{ old('typical_opportunities', $partner->typical_opportunities) }}">
                </label>
                <label>
                    طبيعة التمويل المعتادة
                    <select name="typical_funding">
                        <option value="">بدون</option>
                        @foreach(($options['typical_funding'] ?? collect()) as $option)
                            <option value="{{ $option->label }}" @selected(old('typical_funding', $partner->typical_funding) === $option->label)>{{ $option->label }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            <label>
                مجالات التعاون
                <textarea name="cooperation_areas" rows="3">{{ old('cooperation_areas', $partner->cooperation_areas) }}</textarea>
            </label>
            <label>
                ملاحظات التقييم
                <textarea name="evaluation_notes" rows="3">{{ old('evaluation_notes', $partner->evaluation_notes) }}</textarea>
            </label>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">تحديث</button>
                <a class="link" href="{{ route('partners.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection
