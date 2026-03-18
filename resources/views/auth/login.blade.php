<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الدخول</title>
    <style>
        :root {
            --bg: #f6f2ec;
            --ink: #1f2937;
            --muted: #6b7280;
            --brand: #0f3d3e;
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
            display: grid;
            place-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .card {
            width: min(460px, 94vw);
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 28px;
            box-shadow: var(--shadow);
        }
        .logo {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--brand), #2b6777);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 700;
            margin-bottom: 14px;
        }
        h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }
        .muted {
            color: var(--muted);
            line-height: 1.8;
        }
        label {
            display: block;
            margin-top: 14px;
            font-size: 14px;
        }
        input {
            width: 100%;
            margin-top: 6px;
            padding: 11px 12px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-family: inherit;
        }
        button {
            width: 100%;
            margin-top: 18px;
            padding: 11px 12px;
            background: var(--brand);
            color: #fff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-family: inherit;
        }
        .notice {
            margin-top: 16px;
            padding: 12px;
            border-radius: 12px;
            background: #ecfdf3;
            border: 1px solid #bbf7d0;
            color: #166534;
            line-height: 1.8;
        }
        .error {
            color: #b91c1c;
            margin-top: 10px;
            font-size: 13px;
        }
        .links {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 18px;
        }
        .links a {
            color: var(--brand);
            text-decoration: none;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">LT</div>
        <h1>تسجيل الدخول</h1>
        <div class="muted">أدخل اسم المستخدم وكلمة المرور للوصول إلى النظام أو بوابة الموظف.</div>

        @if(session('registration_credentials'))
            <div class="notice">
                تم إنشاء حسابك بنجاح. اسم المستخدم الخاص بك هو:
                <strong>{{ session('registration_credentials.username') }}</strong>
            </div>
        @endif

        @if(session('status'))
            <div class="notice">{{ session('status') }}</div>
        @endif

        <form method="post" action="{{ route('login.perform') }}">
            @csrf
            <label>
                اسم المستخدم
                <input type="text" name="username" value="{{ old('username') }}" autocomplete="username">
            </label>
            <label>
                كلمة المرور
                <input type="password" name="password" autocomplete="current-password">
            </label>
            @error('username')
                <div class="error">{{ $message }}</div>
            @enderror
            <button type="submit">دخول</button>
        </form>

        <div class="links">
            <a href="{{ route('register') }}">تسجيل موظف جديد</a>
            <a href="{{ route('password.request') }}">استعادة كلمة المرور</a>
            <a href="{{ route('home') }}">العودة إلى الرئيسية</a>
        </div>
    </div>
</body>
</html>
