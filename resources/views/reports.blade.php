@extends('layouts.app')

@section('title', 'التقارير')

@section('content')
    <div class="card">
        <h3>التقارير والمتابعة</h3>
        <p class="muted">تصدير تقارير الفرص والمستفيدين والتمويل والشركاء.</p>
        <div class="grid grid-2" style="margin-top: 10px;">
            <div class="card">
                <h3>تقرير الفرص التدريبية</h3>
                <p class="muted">حسب السنة والنوع وحالة الفرصة.</p>
            </div>
            <div class="card">
                <h3>تقرير المستفيدين</h3>
                <p class="muted">حسب الإدارة والمسمى الوظيفي والفترة.</p>
            </div>
            <div class="card">
                <h3>تقرير التمويل</h3>
                <p class="muted">فرص ممولة بالكامل وجزئيًا والتزامات الوزارة.</p>
            </div>
            <div class="card">
                <h3>تقرير الشركاء</h3>
                <p class="muted">عدد الفرص لكل شريك ونوع التمويل.</p>
            </div>
        </div>
    </div>
@endsection
