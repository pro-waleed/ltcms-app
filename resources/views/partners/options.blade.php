@extends('layouts.app')

@section('title', 'قوائم الشركاء')

@section('content')
    <div class="card" style="margin-bottom: 16px;">
        <h3>قوائم الشركاء</h3>
        <p class="muted">إدارة الخيارات الخاصة بنوع الجهة والمستوى الجغرافي والأهمية الاستراتيجية والقطاع.</p>
        @if(session('status'))
            <p class="success">{{ session('status') }}</p>
        @endif
    </div>

    <div class="grid grid-2">
        @foreach($labels as $key => $label)
            <div class="card">
                <h3>{{ $label }}</h3>
                <form method="post" action="{{ route('partner-options.store') }}" class="form" style="margin-bottom: 12px;">
                    @csrf
                    <input type="hidden" name="category" value="{{ $key }}">
                    <label>
                        إضافة خيار جديد
                        <input type="text" name="label" placeholder="أدخل الخيار">
                    </label>
                    <div style="margin-top: 8px;">
                        <button class="btn" type="submit">إضافة</button>
                    </div>
                </form>

                <table class="table">
                    <thead>
                        <tr>
                            <th>الخيار</th>
                            <th>نشط</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($options[$key] ?? [] as $option)
                            <tr>
                                <td>
                                    <form method="post" action="{{ route('partner-options.update', $option) }}" class="form">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="label" value="{{ $option->label }}">
                                </td>
                                <td>
                                        <input type="checkbox" name="is_active" value="1" @checked($option->is_active)>
                                </td>
                                <td style="white-space: nowrap;">
                                        <button class="link" type="submit">تحديث</button>
                                    </form>
                                    <form method="post" action="{{ route('partner-options.destroy', $option) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="link danger" type="submit">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if(empty($options[$key]))
                            <tr><td colspan="3" class="muted">لا توجد خيارات بعد.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
@endsection
