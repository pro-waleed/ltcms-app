<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'نظام إدارة التدريب')</title>
    <style>
        :root {
            --bg: #f6f2ec;
            --ink: #1f2937;
            --muted: #6b7280;
            --brand: #0f3d3e;
            --accent: #c37b31;
            --card: #ffffff;
            --border: #eadfce;
            --shadow: 0 10px 30px rgba(15, 61, 62, 0.12);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Cairo", "Segoe UI", Tahoma, sans-serif;
            color: var(--ink);
            background: radial-gradient(1200px 600px at 10% 0%, #fff7ed, transparent),
                        radial-gradient(900px 500px at 100% 10%, #eef7f4, transparent),
                        var(--bg);
        }
        .app {
            min-height: 100vh;
            display: grid;
            grid-template-rows: auto 1fr;
        }
        header {
            position: sticky;
            top: 0;
            background: rgba(246, 242, 236, 0.9);
            backdrop-filter: blur(6px);
            border-bottom: 1px solid var(--border);
            z-index: 10;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 18px 24px;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .logo {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--brand), #2b6777);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 700;
            box-shadow: var(--shadow);
        }
        nav {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 12px;
            align-items: center;
        }
        nav a {
            text-decoration: none;
            color: var(--ink);
            background: #fff;
            border: 1px solid var(--border);
            padding: 8px 14px;
            border-radius: 10px;
            transition: 0.2s ease;
        }
        nav a:hover {
            border-color: var(--accent);
            color: var(--brand);
        }
        main {
            padding: 28px 0 48px;
        }
        .grid {
            display: grid;
            gap: 18px;
        }
        .grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 18px;
            box-shadow: var(--shadow);
        }
        .card h3 { margin: 0 0 6px; font-size: 18px; }
        .kpi {
            font-size: 26px;
            font-weight: 700;
            color: var(--brand);
        }
        .muted { color: var(--muted); }
        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 12px;
        }
        .table thead th {
            background: #f3e7d3;
            color: #2d2a26;
            font-weight: 700;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        .table th, .table td {
            text-align: right;
            padding: 12px 14px;
            border-bottom: 1px solid var(--border);
        }
        .table tbody tr:nth-child(even) {
            background: #fffaf3;
        }
        .table tbody tr:hover {
            background: #fff2df;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            background: #fef3c7;
            color: #92400e;
        }
        .btn {
            display: inline-block;
            padding: 8px 14px;
            background: var(--brand);
            color: #fff;
            border-radius: 10px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
        .link {
            color: var(--brand);
            text-decoration: none;
            margin-inline-start: 8px;
        }
        .link.danger { color: #b91c1c; }
        .success {
            margin: 10px 0 0;
            padding: 10px 12px;
            background: #ecfdf3;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            color: #166534;
        }
        .form label {
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-size: 14px;
        }
        .form input, .form select, .form textarea {
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 8px 10px;
            font-family: inherit;
        }
        .search-wrap {
            position: relative;
        }
        .search-form {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 6px 8px;
        }
        .search-form input {
            border: none;
            outline: none;
            padding: 4px 6px;
            min-width: 180px;
        }
        .search-form button {
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 6px 10px;
            cursor: pointer;
        }
        .search-suggest {
            position: absolute;
            top: calc(100% + 6px);
            inset-inline-start: 0;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: var(--shadow);
            width: 320px;
            z-index: 20;
            display: none;
            max-height: 320px;
            overflow: auto;
        }
        .search-suggest.active { display: block; }
        .search-suggest a {
            display: block;
            padding: 8px 12px;
            text-decoration: none;
            color: var(--ink);
            border-bottom: 1px solid var(--border);
            font-size: 14px;
        }
        .search-suggest a:last-child { border-bottom: none; }
        .search-suggest a:hover { background: #fdf6ea; }
        .search-suggest .label { font-size: 12px; color: var(--muted); margin-inline-start: 6px; }
        .logout {
            margin-inline-start: auto;
        }
        .logout button {
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 6px 10px;
            cursor: pointer;
        }
        @media (max-width: 980px) {
            .grid-4 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .grid-2 { grid-template-columns: 1fr; }
            .search-form { width: 100%; }
            .search-suggest { width: 100%; }
        }
        @media (max-width: 640px) {
            .grid-4 { grid-template-columns: 1fr; }
            nav { gap: 8px; }
        }
    </style>
</head>
<body>
<div class="app">
    <header>
        <div class="container">
            <div class="brand">
                <div class="logo">LT</div>
                <div>
                    <div style="font-weight: 700;">نظام إدارة التدريب والتأهيل</div>
                    <div class="muted" style="font-size: 13px;">وزارة الخارجية وشؤون المغتربين - الجمهورية اليمنية</div>
                </div>
            </div>
            @auth
            <nav>
                <a href="/">لوحة المتابعة</a>
                <a href="/opportunities">الفرص التدريبية</a>
                <a href="/nominations">الترشيحات</a>
                <a href="/applications">طلبات المشاركة</a>
                <a href="/employees">الموظفون</a>
                <a href="/partners">الشركاء</a>
                <a href="/funding">التمويل</a>
                <a href="/departments">الإدارات</a>
                <a href="/missions">البعثات</a>
                <a href="/reports">التقارير</a>
                @if(auth()->user()?->hasRole('system_admin'))
                    <a href="/users">المستخدمون</a>
                    <a href="/roles">الأدوار</a>
                @endif
                <div class="search-wrap">
                    <form class="search-form" method="get" action="{{ route('search.index') }}" autocomplete="off">
                        <input type="text" id="quick-search" name="q" value="{{ request('q') }}" placeholder="بحث عن موظف، فرصة، شريك">
                        <button type="submit">بحث</button>
                    </form>
                    <div class="search-suggest" id="search-suggest"></div>
                </div>
                <form class="logout" method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">خروج</button>
                </form>
            </nav>
            @endauth
        </div>
    </header>

    <main>
        <div class="container">
            @yield('content')
        </div>
    </main>
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
        box.innerHTML = items.map(item => {
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
            .then(res => res.json())
            .then(data => render(data.results || []))
            .catch(() => render([]));
    };

    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(fetchSuggestions, 250);
    });

    document.addEventListener('click', (e) => {
        if (!box.contains(e.target) && e.target !== input) {
            box.classList.remove('active');
        }
    });
})();
</script>
</body>
</html>
