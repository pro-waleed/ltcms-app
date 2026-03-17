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
        }
        .card {
            width: min(420px, 92vw);
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 24px;
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
            margin-bottom: 12px;
        }
        label { display: block; margin-top: 12px; font-size: 14px; }
        input {
            width: 100%;
            margin-top: 6px;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-family: inherit;
        }
        button {
            width: 100%;
            margin-top: 16px;
            padding: 10px 12px;
            background: var(--brand);
            color: #fff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }
        .error { color: #b91c1c; margin-top: 10px; font-size: 13px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">LT</div>
        <h3 style="margin: 0 0 6px;">نظام إدارة التدريب والتأهيل</h3>
        <div class="muted" style="font-size: 13px; margin-bottom: 10px;">وزارة الخارجية وشؤون المغتربين</div>

        <form method="post" action="{{ route('login.perform') }}">
            @csrf
            <label>
                اسم المستخدم
                <input type="text" name="username" value="{{ old('username') }}">
            </label>
            <label>
                كلمة المرور
                <input type="password" name="password">
            </label>
            @error('username')
                <div class="error">{{ $message }}</div>
            @enderror
            <button type="submit">تسجيل الدخول</button>
        </form>
    </div>
</body>
</html>
