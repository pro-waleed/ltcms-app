@php
    $lookupId = $lookupId ?? 'employee';
    $fieldName = $fieldName ?? 'employee_id';
    $searchName = $searchName ?? 'employee_search';
    $selectedEmployee = $selectedEmployee ?? null;
    $selectedEmployeeId = old($fieldName, $selectedEmployee?->id);
    $selectedEmployeeLabel = old($searchName, $selectedEmployee ? trim($selectedEmployee->full_name . ' - ' . ($selectedEmployee->employee_no ?: 'بدون رقم')) : '');
    $selectedEmployeeMeta = $selectedEmployee
        ? collect([
            $selectedEmployee->employee_no,
            optional($selectedEmployee->department)->name,
            $selectedEmployee->job_title,
        ])->filter()->implode(' | ')
        : '';
@endphp

<div class="employee-lookup" data-employee-lookup data-results-url="{{ route('search.employees') }}">
    <input type="hidden" name="{{ $fieldName }}" value="{{ $selectedEmployeeId }}" data-employee-id>

    <label for="{{ $lookupId }}_search">
        الموظف
        <input
            id="{{ $lookupId }}_search"
            type="text"
            name="{{ $searchName }}"
            value="{{ $selectedEmployeeLabel }}"
            placeholder="ابحث بالاسم أو الرقم الوظيفي"
            autocomplete="off"
            data-employee-search
        >
        <small class="helper-text">ابدأ بكتابة حرفين على الأقل. هذا الحقل بديل احترافي للقوائم الطويلة.</small>
    </label>

    <div class="lookup-selected @if(!$selectedEmployeeId) is-empty @endif" data-employee-selected>
        @if($selectedEmployeeId)
            <strong>{{ $selectedEmployeeLabel }}</strong>
            <div class="muted">{{ $selectedEmployeeMeta }}</div>
        @else
            <span class="muted">لم يتم اختيار موظف بعد.</span>
        @endif
    </div>

    <div class="lookup-results" data-employee-results></div>
</div>

<script>
(function () {
    const root = document.currentScript.previousElementSibling;
    if (!root || root.dataset.employeeLookupBound === 'true') return;
    root.dataset.employeeLookupBound = 'true';

    const searchInput = root.querySelector('[data-employee-search]');
    const hiddenInput = root.querySelector('[data-employee-id]');
    const selectedBox = root.querySelector('[data-employee-selected]');
    const resultsBox = root.querySelector('[data-employee-results]');
    const url = root.dataset.resultsUrl;

    let timer = null;

    const clearResults = () => {
        resultsBox.innerHTML = '';
        resultsBox.classList.remove('active');
    };

    const renderSelected = (item) => {
        if (!item) {
            hiddenInput.value = '';
            selectedBox.classList.add('is-empty');
            selectedBox.innerHTML = '<span class="muted">لم يتم اختيار موظف بعد.</span>';
            return;
        }

        hiddenInput.value = item.id;
        selectedBox.classList.remove('is-empty');
        selectedBox.innerHTML = `
            <strong>${item.label}</strong>
            <div class="muted">${[item.department, item.job_title].filter(Boolean).join(' | ') || 'بدون تفاصيل إضافية'}</div>
        `;
    };

    const renderResults = (items) => {
        if (!items.length) {
            clearResults();
            return;
        }

        resultsBox.innerHTML = items.map((item) => `
            <button type="button" class="lookup-option" data-id="${item.id}" data-label="${item.label}" data-department="${item.department || ''}" data-job-title="${item.job_title || ''}">
                <strong>${item.label}</strong>
                <span>${[item.department, item.job_title].filter(Boolean).join(' | ') || 'بدون تفاصيل إضافية'}</span>
            </button>
        `).join('');

        resultsBox.classList.add('active');
    };

    const fetchResults = () => {
        const q = searchInput.value.trim();

        if (q.length < 2) {
            clearResults();
            return;
        }

        fetch(`${url}?q=${encodeURIComponent(q)}`)
            .then((response) => response.json())
            .then((data) => renderResults(data.results || []))
            .catch(() => clearResults());
    };

    searchInput.addEventListener('input', () => {
        hiddenInput.value = '';
        clearTimeout(timer);
        timer = setTimeout(fetchResults, 220);
    });

    resultsBox.addEventListener('click', (event) => {
        const button = event.target.closest('.lookup-option');
        if (!button) return;

        const item = {
            id: button.dataset.id,
            label: button.dataset.label,
            department: button.dataset.department,
            job_title: button.dataset.jobTitle,
        };

        searchInput.value = item.label;
        renderSelected(item);
        clearResults();
    });

    document.addEventListener('click', (event) => {
        if (!root.contains(event.target)) {
            clearResults();
        }
    });
})();
</script>
