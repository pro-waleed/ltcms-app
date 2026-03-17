@extends('layouts.app')

@section('title', 'البحث')

@section('content')
    <div class="card" style="margin-bottom: 16px;">
        <h3>البحث الشامل</h3>
        <p class="muted">ابحث عن موظف أو فرصة تدريبية أو شريك مع فلاتر متقدمة.</p>
        <form method="get" action="{{ route('search.index') }}" class="form" style="margin-top: 12px;">
            <div class="grid grid-2">
                <label>
                    كلمة البحث
                    <input type="text" name="q" value="{{ $query }}" placeholder="اسم موظف، رقم فرصة، اسم شريك">
                </label>
                <label>
                    نوع البحث
                    <select name="type">
                        <option value="all" @selected($type === 'all')>الكل</option>
                        <option value="employees" @selected($type === 'employees')>الموظفون</option>
                        <option value="opportunities" @selected($type === 'opportunities')>الفرص التدريبية</option>
                        <option value="partners" @selected($type === 'partners')>الشركاء</option>
                    </select>
                </label>
                <label>
                    الإدارة (للموظفين)
                    <select name="department_id">
                        <option value="">الكل</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected((string) $departmentId === (string) $department->id)>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label>
                    البعثة (للموظفين)
                    <select name="mission_id">
                        <option value="">الكل</option>
                        @foreach($missions as $mission)
                            <option value="{{ $mission->id }}" @selected((string) $missionId === (string) $mission->id)>
                                {{ $mission->name }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label>
                    المسمى الوظيفي (للموظفين)
                    <input type="text" name="job_title" value="{{ $jobTitle }}" placeholder="مثال: مستشار">
                </label>
                <label>
                    حالة الفرصة (للفرص)
                    <select name="opportunity_status">
                        <option value="">الكل</option>
                        @foreach(['draft' => 'مسودة','received' => 'واردة','under_review' => 'قيد الدراسة','open_for_nomination' => 'مفتوحة للترشيح','closed' => 'مغلقة','nominated' => 'تم الترشيح','executed' => 'منفذة','closed_no_benefit' => 'أغلقت دون استفادة','referred' => 'محالة','cancelled' => 'ملغاة'] as $key => $label)
                            <option value="{{ $key }}" @selected($opportunityStatus === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    الشريك (للفرص)
                    <select name="opportunity_partner_id">
                        <option value="">الكل</option>
                        @foreach($partnersList as $partner)
                            <option value="{{ $partner->id }}" @selected((string) $opportunityPartnerId === (string) $partner->id)>
                                {{ $partner->name }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label>
                    نمط التنفيذ (للفرص)
                    <select name="opportunity_mode">
                        <option value="">الكل</option>
                        <option value="onsite" @selected($opportunityMode === 'onsite')>حضوري</option>
                        <option value="online" @selected($opportunityMode === 'online')>أونلاين</option>
                        <option value="hybrid" @selected($opportunityMode === 'hybrid')>هجين</option>
                    </select>
                </label>
                <label>
                    سنة البداية (للفرص)
                    <input type="number" name="opportunity_year" value="{{ $opportunityYear }}" placeholder="2026">
                </label>
                <label>
                    الدولة (للشركاء)
                    <input type="text" name="partner_country" value="{{ $partnerCountry }}" placeholder="مثال: ألمانيا">
                </label>
                <label>
                    نوع الشريك (للشركاء)
                    <input type="text" name="partner_type" value="{{ $partnerType }}" placeholder="مثال: منظمة دولية">
                </label>
            </div>
            <div style="margin-top: 12px;">
                <button class="btn" type="submit">بحث</button>
                <a class="link" href="{{ route('search.index') }}">إعادة ضبط</a>
            </div>
        </form>
    </div>

    @if($query === '')
        <div class="card">
            <p class="muted">أدخل كلمة بحث لعرض النتائج.</p>
        </div>
    @else
        @if($type === 'all' || $type === 'employees')
            <div class="card" style="margin-bottom: 16px;">
                <h3>الموظفون ({{ $employees->count() }})</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>الرقم الوظيفي</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td>{{ $employee->full_name }}</td>
                                <td>{{ $employee->employee_no }}</td>
                                <td><a class="link" href="{{ route('employees.edit', $employee) }}">عرض</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="muted">لا توجد نتائج.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        @if($type === 'all' || $type === 'opportunities')
            <div class="card" style="margin-bottom: 16px;">
                <h3>الفرص التدريبية ({{ $opportunities->count() }})</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>العنوان</th>
                            <th>الرقم المرجعي</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($opportunities as $opportunity)
                            <tr>
                                <td>{{ $opportunity->title }}</td>
                                <td>{{ $opportunity->reference_no }}</td>
                                <td><a class="link" href="{{ route('opportunities.edit', $opportunity) }}">عرض</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="muted">لا توجد نتائج.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        @if($type === 'all' || $type === 'partners')
            <div class="card">
                <h3>الشركاء ({{ $partners->count() }})</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>اسم الشريك</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($partners as $partner)
                            <tr>
                                <td>{{ $partner->name }}</td>
                                <td><a class="link" href="{{ route('partners.edit', $partner) }}">عرض</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="muted">لا توجد نتائج.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    @endif
@endsection
