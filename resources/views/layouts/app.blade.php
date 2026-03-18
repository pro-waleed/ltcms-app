<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0f3d3e">
    <title>@yield('title', 'نظام إدارة التدريب')</title>
    <style>
        :root {
            --bg: #f4efe7;
            --bg-soft: #fbf8f3;
            --ink: #16302f;
            --muted: #6c766f;
            --brand: #123f43;
            --brand-2: #1e5d63;
            --accent: #bf7c36;
            --accent-soft: #f4e3cf;
            --card: rgba(255, 255, 255, 0.88);
            --line: #e6d8c4;
            --success-bg: #ebf8ef;
            --success-text: #17623f;
            --error-bg: #fdf0f0;
            --error-text: #8f1d1d;
            --warning-bg: #fff7e7;
            --warning-text: #9a5a0f;
            --shadow-lg: 0 24px 60px rgba(18, 63, 67, 0.14);
            --shadow-md: 0 12px 30px rgba(18, 63, 67, 0.1);
            --radius-lg: 24px;
            --radius-md: 18px;
            --radius-sm: 12px;
        }

        * { box-sizing: border-box; }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: "Cairo", "Tajawal", "Segoe UI", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(1000px 480px at 0% 0%, rgba(255, 242, 219, 0.85), transparent 70%),
                radial-gradient(900px 460px at 100% 0%, rgba(219, 238, 233, 0.8), transparent 68%),
                linear-gradient(180deg, #f8f4ed 0%, #f3ede3 100%);
        }

        a, button, input, select, textarea {
            transition: all 0.22s ease;
        }

        .app-shell {
            min-height: 100vh;
            display: grid;
            grid-template-rows: auto 1fr auto;
        }

        .site-header {
            position: sticky;
            top: 0;
            z-index: 40;
            backdrop-filter: blur(16px);
            background: rgba(249, 245, 239, 0.84);
            border-bottom: 1px solid rgba(230, 216, 196, 0.82);
        }

        .container {
            width: min(1240px, calc(100% - 32px));
            margin: 0 auto;
        }

        .header-inner {
            padding: 16px 0 12px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .logo {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            font-weight: 800;
            letter-spacing: 0.08em;
            color: #fff;
            text-decoration: none;
            background:
                linear-gradient(145deg, rgba(255,255,255,0.14), rgba(255,255,255,0)),
                linear-gradient(145deg, var(--brand), var(--brand-2));
            box-shadow: var(--shadow-md);
        }

        .brand-copy {
            min-width: 0;
        }

        .brand-title {
            font-weight: 800;
            font-size: 1.05rem;
            margin: 0 0 4px;
        }

        .brand-subtitle {
            color: var(--muted);
            font-size: 0.86rem;
            line-height: 1.7;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .nav-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-top: 14px;
            flex-wrap: wrap;
        }

        .nav-pills {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            min-width: 0;
        }

        .nav-pill {
            text-decoration: none;
            color: var(--ink);
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(230, 216, 196, 0.95);
            border-radius: 999px;
            padding: 10px 14px;
            font-size: 0.92rem;
            box-shadow: 0 6px 16px rgba(18, 63, 67, 0.05);
        }

        .nav-pill:hover,
        .nav-pill:focus-visible {
            color: var(--brand);
            border-color: rgba(191, 124, 54, 0.7);
            transform: translateY(-1px);
        }

        .main-content {
            padding: 30px 0 46px;
        }

        .site-footer {
            position: relative;
            margin-top: 8px;
            padding: 0 0 28px;
        }

        .footer-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            flex-wrap: wrap;
            padding: 18px 22px;
            border-radius: 22px;
            border: 1px solid rgba(230, 216, 196, 0.95);
            background:
                linear-gradient(135deg, rgba(255,255,255,0.86), rgba(252, 247, 239, 0.96)),
                linear-gradient(135deg, rgba(18,63,67,0.04), rgba(191,124,54,0.03));
            box-shadow: var(--shadow-md);
        }

        .footer-title {
            font-weight: 800;
            color: var(--brand);
            margin-bottom: 4px;
        }

        .footer-meta {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
            color: var(--muted);
            font-size: 0.92rem;
        }

        .footer-meta strong {
            color: var(--ink);
            font-weight: 800;
        }

        .page-stack {
            display: grid;
            gap: 18px;
            animation: fadeUp 0.45s ease;
        }

        .card {
            background: var(--card);
            border: 1px solid rgba(230, 216, 196, 0.98);
            border-radius: var(--radius-md);
            padding: 20px;
            box-shadow: var(--shadow-md);
        }

        .card.soft {
            background: rgba(255, 252, 247, 0.9);
        }

        .card h1,
        .card h2,
        .card h3 {
            margin: 0 0 8px;
        }

        .hero {
            display: grid;
            grid-template-columns: minmax(0, 1.35fr) minmax(320px, 0.95fr);
            gap: 18px;
            align-items: stretch;
        }

        .hero-panel {
            position: relative;
            overflow: hidden;
            padding: 30px;
            border-radius: var(--radius-lg);
            color: #fff;
            background:
                radial-gradient(circle at top left, rgba(255,255,255,0.18), transparent 30%),
                linear-gradient(135deg, #103c3f 0%, #19555b 58%, #26656b 100%);
            box-shadow: var(--shadow-lg);
        }

        .hero-panel::after {
            content: "";
            position: absolute;
            inset-inline-end: -40px;
            inset-block-end: -50px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
        }

        .hero-panel > * {
            position: relative;
            z-index: 1;
        }

        .hero-panel p,
        .hero-panel .muted-light {
            color: rgba(255,255,255,0.86);
        }

        .hero-metrics {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-top: 22px;
        }

        .metric-chip {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.14);
            border-radius: 16px;
            padding: 14px;
        }

        .metric-chip strong {
            display: block;
            font-size: 1.4rem;
            margin-bottom: 4px;
        }

        .grid {
            display: grid;
            gap: 18px;
        }

        .grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }

        .stat-card {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .stat-label {
            color: var(--muted);
            font-size: 0.92rem;
        }

        .kpi {
            font-size: 2rem;
            line-height: 1;
            font-weight: 800;
            color: var(--brand);
        }

        .muted { color: var(--muted); }

        .section-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .inline-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 10px 16px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            color: #fff;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
            font-size: 0.95rem;
            box-shadow: 0 8px 22px rgba(18, 63, 67, 0.14);
        }

        .btn:hover,
        .btn:focus-visible {
            transform: translateY(-1px);
            filter: brightness(1.03);
        }

        .btn.alt {
            background: rgba(255,255,255,0.9);
            color: var(--brand);
            border: 1px solid rgba(18, 63, 67, 0.18);
            box-shadow: none;
        }

        .link {
            color: var(--brand);
            text-decoration: none;
            font-weight: 700;
        }

        .link.danger { color: #9f1d1d; }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 5px 11px;
            font-size: 0.78rem;
            font-weight: 700;
            background: #f9e9d3;
            color: #8d4c0f;
        }

        .badge.info { background: #e0effb; color: #1958a5; }
        .badge.success { background: #dcf3e5; color: #17623f; }
        .badge.warning { background: #fff0d2; color: #9a5a0f; }

        .alert,
        .success,
        .error-box {
            border-radius: 16px;
            padding: 14px 16px;
            border: 1px solid transparent;
        }

        .success {
            background: var(--success-bg);
            color: var(--success-text);
            border-color: rgba(23, 98, 63, 0.14);
        }

        .error-box {
            background: var(--error-bg);
            color: var(--error-text);
            border-color: rgba(143, 29, 29, 0.14);
        }

        .panel-note {
            padding: 14px 16px;
            border-radius: 16px;
            background: var(--warning-bg);
            color: var(--warning-text);
            border: 1px solid rgba(191, 124, 54, 0.18);
        }

        .stack {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .empty {
            padding: 18px;
            border: 1px dashed rgba(18, 63, 67, 0.18);
            border-radius: 14px;
            background: rgba(255,255,255,0.58);
            color: var(--muted);
        }

        .form label {
            display: flex;
            flex-direction: column;
            gap: 7px;
            font-size: 0.95rem;
            margin-bottom: 14px;
        }

        .form input,
        .form select,
        .form textarea {
            min-height: 46px;
            border: 1px solid rgba(230, 216, 196, 1);
            border-radius: 12px;
            padding: 10px 12px;
            font-family: inherit;
            font-size: 0.95rem;
            color: var(--ink);
            background: rgba(255,255,255,0.96);
        }

        .form textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form input:focus,
        .form select:focus,
        .form textarea:focus,
        .search-form input:focus {
            outline: none;
            border-color: rgba(18, 63, 67, 0.4);
            box-shadow: 0 0 0 4px rgba(18, 63, 67, 0.08);
        }

        .search-wrap {
            position: relative;
        }

        .search-form {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: min(100%, 340px);
            padding: 6px 8px;
            border-radius: 14px;
            border: 1px solid rgba(230, 216, 196, 0.98);
            background: rgba(255,255,255,0.95);
            box-shadow: 0 10px 18px rgba(18, 63, 67, 0.06);
        }

        .search-form input {
            flex: 1;
            border: none;
            background: transparent;
            min-width: 160px;
            font-family: inherit;
            font-size: 0.94rem;
        }

        .search-form input:focus {
            box-shadow: none;
        }

        .search-form button {
            min-height: 36px;
            border: none;
            border-radius: 10px;
            padding: 0 12px;
            cursor: pointer;
            background: linear-gradient(135deg, var(--accent), #cf8a3f);
            color: #fff;
            font-family: inherit;
        }

        .search-suggest {
            position: absolute;
            inset-inline-start: 0;
            top: calc(100% + 8px);
            width: min(100%, 360px);
            max-height: 320px;
            overflow: auto;
            border-radius: 18px;
            border: 1px solid rgba(230, 216, 196, 1);
            background: rgba(255,255,255,0.98);
            box-shadow: var(--shadow-lg);
            display: none;
            z-index: 50;
        }

        .search-suggest.active { display: block; }

        .search-suggest a {
            display: block;
            text-decoration: none;
            color: var(--ink);
            padding: 10px 14px;
            border-bottom: 1px solid rgba(230, 216, 196, 0.75);
            font-size: 0.92rem;
        }

        .search-suggest a:last-child { border-bottom: none; }
        .search-suggest a:hover { background: rgba(244, 227, 207, 0.36); }
        .search-suggest .label { color: var(--muted); font-size: 0.78rem; margin-inline-start: 8px; }

        .helper-text {
            color: var(--muted);
            font-size: 0.82rem;
        }

        .employee-lookup {
            position: relative;
            display: grid;
            gap: 10px;
        }

        .lookup-selected {
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid rgba(230, 216, 196, 1);
            background: rgba(255, 251, 245, 0.92);
        }

        .lookup-selected.is-empty {
            background: rgba(255,255,255,0.72);
        }

        .lookup-results {
            display: none;
            position: absolute;
            inset-inline-start: 0;
            inset-inline-end: 0;
            top: calc(100% - 2px);
            z-index: 25;
            max-height: 280px;
            overflow: auto;
            border-radius: 16px;
            border: 1px solid rgba(230, 216, 196, 1);
            background: rgba(255,255,255,0.98);
            box-shadow: var(--shadow-lg);
        }

        .lookup-results.active {
            display: block;
        }

        .lookup-option {
            width: 100%;
            border: none;
            border-bottom: 1px solid rgba(230, 216, 196, 0.9);
            background: transparent;
            text-align: right;
            padding: 12px 14px;
            cursor: pointer;
            font-family: inherit;
        }

        .lookup-option strong,
        .lookup-option span {
            display: block;
        }

        .lookup-option span {
            margin-top: 4px;
            color: var(--muted);
            font-size: 0.84rem;
        }

        .lookup-option:hover {
            background: rgba(244, 227, 207, 0.32);
        }

        .logout button {
            min-height: 42px;
            border: 1px solid rgba(230, 216, 196, 1);
            border-radius: 12px;
            padding: 0 14px;
            background: rgba(255,255,255,0.92);
            color: var(--ink);
            cursor: pointer;
            font-family: inherit;
        }

        .logout button:hover {
            border-color: rgba(18, 63, 67, 0.24);
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            overflow: hidden;
            margin-top: 10px;
        }

        .table thead th {
            position: sticky;
            top: 0;
            background: #f3e6d3;
            color: #2f302d;
            font-weight: 800;
        }

        .table th,
        .table td {
            text-align: right;
            padding: 13px 14px;
            border-bottom: 1px solid rgba(230, 216, 196, 0.96);
            vertical-align: top;
        }

        .table tbody tr:nth-child(even) {
            background: rgba(255, 249, 240, 0.68);
        }

        .table tbody tr:hover {
            background: rgba(244, 227, 207, 0.38);
        }

        .surface-list {
            display: grid;
            gap: 12px;
        }

        .surface-item {
            padding: 15px 16px;
            border-radius: 16px;
            border: 1px solid rgba(230, 216, 196, 1);
            background: rgba(255,255,255,0.74);
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 1080px) {
            .hero,
            .grid-4,
            .grid-3 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .hero {
                grid-template-columns: 1fr;
            }

            .hero-metrics {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 760px) {
            .container {
                width: min(100%, calc(100% - 24px));
            }

            .topbar,
            .nav-row {
                align-items: stretch;
            }

            .grid-4,
            .grid-3,
            .grid-2,
            .hero-metrics {
                grid-template-columns: 1fr;
            }

            .search-form,
            .search-suggest {
                width: 100%;
                min-width: 100%;
            }

            .footer-card {
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
<div class="app-shell">
    <header class="site-header">
        <div class="container header-inner">
            <div class="topbar">
                <div class="brand">
                    <a href="{{ route('home') }}" class="logo">LT</a>
                    <div class="brand-copy">
                        <div class="brand-title">نظام إدارة التدريب والتأهيل</div>
                        <div class="brand-subtitle">منصة موحدة لإدارة الفرص التدريبية والطلبات والترشيحات والسجل التدريبي</div>
                    </div>
                </div>

                <div class="header-actions">
                    @guest
                        <a class="nav-pill" href="{{ route('home') }}">الرئيسية</a>
                        <a class="nav-pill" href="{{ route('login') }}">تسجيل الدخول</a>
                        <a class="btn" href="{{ route('register') }}">تسجيل موظف جديد</a>
                    @else
                        <span class="badge info">{{ auth()->user()->full_name }}</span>
                    @endguest
                </div>
            </div>

            @auth
                @php($isEmployeePortal = (bool) auth()->user()?->employee_id)
                <div class="nav-row">
                    <div class="nav-pills">
                        @if($isEmployeePortal)
                            <a class="nav-pill" href="{{ route('portal.dashboard') }}">بوابة الموظف</a>
                            <a class="nav-pill" href="{{ route('portal.opportunities') }}">الفرص المتاحة</a>
                            <a class="nav-pill" href="{{ route('portal.applications') }}">طلباتي</a>
                            <a class="nav-pill" href="{{ route('portal.training-history') }}">السجل التدريبي</a>
                            <a class="nav-pill" href="{{ route('portal.profile') }}">البيانات الشخصية</a>
                            <a class="nav-pill" href="{{ route('portal.password') }}">كلمة المرور</a>
                        @else
                            <a class="nav-pill" href="{{ route('dashboard') }}">لوحة المتابعة</a>
                            <a class="nav-pill" href="{{ route('opportunities.index') }}">الفرص</a>
                            <a class="nav-pill" href="{{ route('applications.index') }}">الطلبات</a>
                            <a class="nav-pill" href="{{ route('nominations.index') }}">الترشيحات</a>
                            <a class="nav-pill" href="{{ route('employees.index') }}">الموظفون</a>
                            <a class="nav-pill" href="{{ route('partners.index') }}">الشركاء</a>
                            <a class="nav-pill" href="{{ route('funding.index') }}">التمويل</a>
                            <a class="nav-pill" href="{{ route('departments.index') }}">الإدارات</a>
                            <a class="nav-pill" href="{{ route('missions.index') }}">البعثات</a>
                            <a class="nav-pill" href="{{ route('reports.index') }}">التقارير</a>
                            @if(auth()->user()?->hasRole('system_admin'))
                                <a class="nav-pill" href="{{ route('users.index') }}">المستخدمون</a>
                                <a class="nav-pill" href="{{ route('roles.index') }}">الأدوار</a>
                            @endif
                        @endif
                    </div>

                    <div class="inline-actions">
                        @unless($isEmployeePortal)
                            <div class="search-wrap">
                                <form class="search-form" method="get" action="{{ route('search.index') }}" autocomplete="off">
                                    <input type="text" id="quick-search" name="q" value="{{ request('q') }}" placeholder="ابحث عن موظف أو فرصة أو شريك">
                                    <button type="submit">بحث</button>
                                </form>
                                <div class="search-suggest" id="search-suggest"></div>
                            </div>
                        @endunless

                        <form class="logout" method="post" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">خروج</button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </header>

    <main class="main-content">
        <div class="container page-stack">
            @if(session('status'))
                <div class="success">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="error-box">
                    <strong>يرجى مراجعة البيانات التالية:</strong>
                    <ul style="margin: 8px 0 0; padding-inline-start: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-card">
                <div>
                    <div class="footer-title">نظام التدريب والتأهيل - الإدارة العامة للتخطيط والبحوث</div>
                    <div class="muted">جميع الحقوق الفكرية محفوظة ضمن إطار العمل المؤسسي للنظام.</div>
                </div>
                <div class="footer-meta">
                    <span><strong>تطوير:</strong> م. وليد العماري</span>
                    <span><strong>إشراف:</strong> السفير طارق عبداللطيف ضيف الله</span>
                </div>
            </div>
        </div>
    </footer>
</div>

<script>
(function () {
    const input = document.getElementById('quick-search');
    const box = document.getElementById('search-suggest');
    if (!input || !box) return;

    let timer = null;

    const render = (items) => {
        if (!items.length) {
            box.classList.remove('active');
            box.innerHTML = '';
            return;
        }

        box.innerHTML = items.map((item) => {
            const label = item.type === 'employee' ? 'موظف' : (item.type === 'opportunity' ? 'فرصة' : 'شريك');
            return `<a href="${item.url}">${item.label} <span class="label">${label}</span></a>`;
        }).join('');

        box.classList.add('active');
    };

    const fetchSuggestions = () => {
        const q = input.value.trim();
        if (q.length < 2) {
            render([]);
            return;
        }

        fetch(`{{ route('search.suggest') }}?q=${encodeURIComponent(q)}`)
            .then((res) => res.json())
            .then((data) => render(data.results || []))
            .catch(() => render([]));
    };

    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(fetchSuggestions, 220);
    });

    document.addEventListener('click', (event) => {
        if (!box.contains(event.target) && event.target !== input) {
            box.classList.remove('active');
        }
    });
})();
</script>
</body>
</html>
