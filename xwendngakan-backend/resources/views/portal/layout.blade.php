<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Khwenden — پلاتفۆرمی پەروەردەیی')</title>
    <link rel="icon" href="{{ asset('images/app_logo.png') }}?v={{ time() }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('images/app_logo.png') }}?v={{ time() }}">
    <meta name="description" content="Khwenden — تۆمارکردنی دامەزراوەت و بەڕێوەبردنی پۆستەکانت بە ئاسانی">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100..900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        :root {
            --gold:    #e2b042;
            --gold-lt: #fbbf24;
            --gold-dk: #b88728;
            --bg:      #080c14;
            --bg2:     #0f172a;
            --bg3:     #1e293b;
            --border:  rgba(226, 176, 66, 0.12);
            --border2: rgba(226, 176, 66, 0.28);
            --txt:     #f8fafc;
            --txt2:    #94a3b8;
            --txt3:    #64748b;
            --red:     #ef4444;
            --green:   #10b981;
            --grad:    linear-gradient(135deg, #b88728, #fbbf24);
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            --shadow-glow: 0 0 15px rgba(226, 176, 66, 0.15);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Vazirmatn', sans-serif;
            background: var(--bg);
            color: var(--txt);
            min-height: 100vh;
            direction: rtl;
            line-height: 1.75;
            overflow-x: hidden;
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: rgba(226,176,66,.2); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gold); }

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed; top: 0; right: 0; left: 0; z-index: 1000;
            padding: 0 2.5rem; height: 68px;
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(8, 12, 20, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
        }
        .nav-brand { display:flex; align-items:center; gap:10px; text-decoration:none; }
        .nav-logo {
            width: 38px; height: 38px; border-radius: 11px;
            background: var(--grad);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem; border: 1px solid var(--border2);
            box-shadow: var(--shadow-glow);
        }
        .nav-brand-text { font-size: 1.35rem; font-weight: 800; color: var(--txt); letter-spacing: -0.5px; }
        .nav-brand-text span { color: var(--gold-lt); }

        .nav-links { display: flex; align-items: center; gap: .75rem; }

        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 22px; border-radius: 12px;
            font-family: inherit; font-size: .9rem; font-weight: 700;
            cursor: pointer; text-decoration: none; border: none;
            transition: all .25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-ghost {
            background: rgba(255, 255, 255, 0.03); color: var(--txt2);
            border: 1px solid var(--border);
        }
        .btn-ghost:hover { border-color: var(--gold-lt); color: var(--gold-lt); background: rgba(226, 176, 66, 0.08); transform: translateY(-1px); }
        .btn-primary {
            background: var(--grad); color: #080c14;
            border: 1px solid var(--border2);
            box-shadow: 0 4px 12px rgba(226, 176, 66, 0.2);
            font-weight: 800;
        }
        .btn-primary:hover { opacity: .95; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(226, 176, 66, 0.35); }
        .btn-sm { padding: 6px 16px; font-size: .82rem; border-radius: 8px; }

        /* ===== ALERTS ===== */
        .alert {
            padding: 12px 18px; border-radius: 12px;
            margin-bottom: 1.25rem; font-size: .88rem; font-weight: 600;
            border: 1px solid; display: flex; align-items: center; gap: 10px;
            box-shadow: var(--shadow-sm);
        }
        .alert-success { background: rgba(16,185,129,.06);  border-color: rgba(16,185,129,.25); color: #34d399; }
        .alert-error   { background: rgba(239,68,68,.06);   border-color: rgba(239,68,68,.25);  color: #f87171; }
        .alert-warning { background: rgba(226,176,66,.06);  border-color: var(--border2);       color: var(--gold-lt); }

        /* ===== TOAST NOTIFICATIONS ===== */
        #toast-container {
            position: fixed; bottom: 20px; left: 20px; z-index: 9999; /* Left side for RTL */
            display: flex; flex-direction: column; gap: 10px; pointer-events: none;
        }
        .toast {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 14px 22px;
            color: var(--txt);
            font-weight: 700; font-size: 0.95rem;
            display: flex; align-items: center; gap: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5), 0 0 0 1px rgba(226, 176, 66, 0.1);
            transform: translateX(-120%);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        .toast.show { transform: translateX(0); opacity: 1; }
        .toast-success { border-right: 4px solid #10b981; }
        .toast-error { border-right: 4px solid #ef4444; }
        .toast-icon { font-size: 1.3rem; }

        /* ===== PAGE WRAPPER ===== */
        .page-wrapper { padding-top: 68px; min-height: 100vh; }

        @media (max-width: 640px) {
            .navbar { padding: 0 1.25rem; }
            #toast-container { left: 10px; right: 10px; bottom: 80px; align-items: center; }
            .toast { width: 100%; justify-content: center; transform: translateY(100%); }
            .toast.show { transform: translateY(0); }
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('portal.home') }}" class="nav-brand">
            <div class="nav-logo" style="width: 38px; height: 38px; background:transparent; border:none; box-shadow:none;">
                <img src="{{ asset('images/app_logo.png') }}?v={{ time() }}" alt="Khwenden" style="width:100%; height:100%; object-fit:contain; transform: scale(1.15);">
            </div>
            <div class="nav-brand-text"><span>Edu</span>Book</div>
        </a>
        <div class="nav-links">
            @auth
                @if(auth()->user()->is_approved)
                <a href="{{ route('portal.dashboard') }}" class="btn btn-ghost">داشبۆرد</a>
                @endif
                <form method="POST" action="{{ route('portal.logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-ghost">دەرچوون</button>
                </form>
            @else
                <a href="{{ route('portal.login') }}" class="btn btn-ghost">چوونەژوورەوە</a>
                <a href="{{ route('portal.register') }}" class="btn btn-primary">تۆمارکردن</a>
            @endauth
        </div>
    </nav>

    <div class="page-wrapper">
        @yield('content')
    </div>

    <div id="toast-container"></div>

    <script>
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        const icon = type === 'success' ? '✅' : '⚠️';
        toast.innerHTML = `<span class="toast-icon">${icon}</span><span>${message}</span>`;
        container.appendChild(toast);
        
        requestAnimationFrame(() => {
            requestAnimationFrame(() => toast.classList.add('show'));
        });
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 400);
        }, 4000);
    }
    </script>

    @if(session('success'))
    <script>document.addEventListener('DOMContentLoaded', () => showToast("{{ session('success') }}", 'success'));</script>
    @endif
    @if(session('error'))
    <script>document.addEventListener('DOMContentLoaded', () => showToast("{{ session('error') }}", 'error'));</script>
    @endif

    @yield('scripts')
</body>
</html>
