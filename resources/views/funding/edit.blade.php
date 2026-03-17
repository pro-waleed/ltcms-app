@extends('layouts.app')

@section('title', 'تعديل تمويل')

@section('content')
    <div class="card">
        <h3>تعديل بيانات التمويل</h3>
        <form method="post" action="{{ route('funding.update', $funding) }}" class="form">
            @csrf
            @method('PUT')
            <div class="grid grid-2">
                <label>
                    نوع التمويل
                    <select name="funding_type">
                        @foreach(['fully_funded' => 'ممول بالكامل','partially_funded' => 'ممول جزئيًا','not_funded' => 'غير ممول','co_funded' => 'تمويل مشترك'] as $key => $label)
                            <option value="{{ $key }}" @selected($funding->funding_type === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    ربط بفرصة
                    <select name="opportunity_id">
                        <option value="">بدون</option>
                        @foreach($opportunities as $opportunity)
                            <option value="{{ $opportunity->id }}" @selected($opportunity->funding_detail_id === $funding->id)>{{ $opportunity->reference_no }} - {{ $opportunity->title }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    الرسوم التدريبية
                    <select name="training_fees">
                        @foreach(['included' => 'مشمول','excluded' => 'غير مشمول','unspecified' => 'غير محدد'] as $key => $label)
                            <option value="{{ $key }}" @selected($funding->training_fees === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    التذاكر الدولية
                    <select name="international_tickets">
                        @foreach(['included' => 'مشمول','excluded' => 'غير مشمول','unspecified' => 'غير محدد'] as $key => $label)
                            <option value="{{ $key }}" @selected($funding->international_tickets === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    التذاكر الداخلية
                    <select name="domestic_tickets">
                        @foreach(['included' => 'مشمول','excluded' => 'غير مشمول','unspecified' => 'غير محدد'] as $key => $label)
                            <option value="{{ $key }}" @selected($funding->domestic_tickets === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    الإقامة
                    <select name="accommodation">
                        @foreach(['included' => 'مشمول','excluded' => 'غير مشمول','unspecified' => 'غير محدد'] as $key => $label)
                            <option value="{{ $key }}" @selected($funding->accommodation === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    الوجبات
                    <select name="meals">
                        @foreach(['included' => 'مشمول','excluded' => 'غير مشمول','unspecified' => 'غير محدد'] as $key => $label)
                            <option value="{{ $key }}" @selected($funding->meals === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    المواصلات المحلية
                    <select name="local_transport">
                        @foreach(['included' => 'مشمول','excluded' => 'غير مشمول','unspecified' => 'غير محدد'] as $key => $label)
                            <option value="{{ $key }}" @selected($funding->local_transport === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    التأمين الصحي
                    <select name="health_insurance">
                        @foreach(['included' => 'مشمول','excluded' => 'غير مشمول','unspecified' => 'غير محدد'] as $key => $label)
                            <option value="{{ $key }}" @selected($funding->health_insurance === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    رسوم التأشيرة
                    <select name="visa_fees">
                        @foreach(['included' => 'مشمول','excluded' => 'غير مشمول','unspecified' => 'غير محدد'] as $key => $label)
                            <option value="{{ $key }}" @selected($funding->visa_fees === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    بدل يومي
                    <select name="per_diem">
                        @foreach(['included' => 'مشمول','excluded' => 'غير مشمول','unspecified' => 'غير محدد'] as $key => $label)
                            <option value="{{ $key }}" @selected($funding->per_diem === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    مواد تدريبية
                    <select name="training_materials">
                        @foreach(['included' => 'مشمول','excluded' => 'غير مشمول','unspecified' => 'غير محدد'] as $key => $label)
                            <option value="{{ $key }}" @selected($funding->training_materials === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    دعم تقني/إنترنت
                    <select name="tech_support">
                        @foreach(['included' => 'مشمول','excluded' => 'غير مشمول','unspecified' => 'غير محدد'] as $key => $label)
                            <option value="{{ $key }}" @selected($funding->tech_support === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            <label>
                التزامات الوزارة
                <textarea name="ministry_obligations" rows="3">{{ old('ministry_obligations', $funding->ministry_obligations) }}</textarea>
            </label>
            <label>
                ملاحظات
                <textarea name="notes" rows="3">{{ old('notes', $funding->notes) }}</textarea>
            </label>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">تحديث</button>
                <a class="link" href="{{ route('funding.index') }}">رجوع</a>
            </div>
        </form>
    </div>
@endsection
