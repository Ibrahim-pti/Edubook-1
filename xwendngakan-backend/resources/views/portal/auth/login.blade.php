@extends('portal.layout')
@section('title', 'چوونەژوورەوە — Khwenden')
@section('styles')
<style>
.auth-page {
    min-height:calc(100vh - 68px);display:flex;align-items:center;justify-content:center;
    padding:2.5rem 1.5rem;background:var(--bg);
    background-image:
        radial-gradient(ellipse 60% 50% at 50% 0%, rgba(226, 176, 66, 0.08) 0%, transparent 60%);
}
.auth-card {
    width:100%;max-width:420px;
    background:rgba(15, 23, 42, 0.55);border:1px solid var(--border2);
    border-radius:24px;padding:2.5rem;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.4), var(--shadow-glow);
}
.auth-header { text-align:center;margin-bottom:2rem; }
.auth-logo {
    width:58px;height:58px;background:var(--grad);
    border:1px solid var(--border2);border-radius:15px;
    display:flex;align-items:center;justify-content:center;
    font-size:1.75rem;margin:0 auto 1.1rem;
    box-shadow: var(--shadow-glow);
}
.auth-title { font-size:1.6rem;font-weight:900;color:var(--txt);margin-bottom:.4rem; }
.auth-sub   { color:var(--txt2);font-size:.9rem; }
.form-group { margin-bottom:1.15rem; }
.form-label { display:block;font-size:.84rem;font-weight:700;color:var(--txt2);margin-bottom:6px; }
.form-input {
    width:100%;padding:12px 15px;
    background:rgba(30, 41, 59, 0.45);border:1px solid var(--border);
    border-radius:11px;color:var(--txt);
    font-family:inherit;font-size:.92rem;outline:none;
    transition:all .2s cubic-bezier(0.4, 0, 0.2, 1);direction:rtl;
}
.form-input:focus {
    border-color:var(--gold-lt);
    background:rgba(30, 41, 59, 0.65);
    box-shadow: 0 0 0 3px rgba(226, 176, 66, 0.12);
}
.form-input::placeholder { color:var(--txt3); }
.form-input.err { border-color:var(--red); }
.remember-row { display:flex;align-items:center;gap:8px;margin-bottom:1.5rem; }
.remember-row input { accent-color:var(--gold-lt);width:16px;height:16px;cursor:pointer; }
.remember-row label { font-size:.88rem;color:var(--txt2);cursor:pointer; }
.btn-full {
    width:100%;justify-content:center;padding:13px;font-size:1rem;
    border-radius:12px;background:var(--grad);color:#080c14;
    font-family:inherit;font-weight:800;border:1px solid var(--border2);
    cursor:pointer;box-shadow: 0 4px 12px rgba(226, 176, 66, 0.15);
    transition:all .25s cubic-bezier(0.4, 0, 0.2, 1);
    display:flex;align-items:center;gap:6px;
}
.btn-full:hover { opacity:.95;transform:translateY(-1px);box-shadow: 0 6px 16px rgba(226, 176, 66, 0.3); }
.btn-full:active { transform:translateY(0); }
.divider { display:flex;align-items:center;gap:12px;margin:1.5rem 0;color:var(--txt3);font-size:.85rem; }
.divider::before,.divider::after { content:'';flex:1;height:1px;background:var(--border); }
.auth-link { text-align:center;font-size:.9rem;color:var(--txt2); }
.auth-link a { color:var(--gold-lt);font-weight:700;text-decoration:none; }
.auth-link a:hover { color:var(--gold); }
</style>
@endsection

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">📚</div>
            <h1 class="auth-title">بەخێربێیتەوە</h1>
            <p class="auth-sub">بچووە ژوورەوە بۆ بەڕێوەبردنی دامەزراوەکەت</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">⚠ {{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('portal.login.submit') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">ئیمەیڵ</label>
                <input id="email" type="email" name="email" class="form-input {{ $errors->has('email') ? 'err' : '' }}"
                    placeholder="example@email.com" value="{{ old('email') }}" required autocomplete="email">
            </div>
            <div class="form-group">
                <label class="form-label" for="password">وشەی نهێنی</label>
                <input id="password" type="password" name="password" class="form-input"
                    placeholder="••••••••" required autocomplete="current-password">
            </div>
            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">لە بیرم بهێلەرەوە</label>
            </div>
            <button type="submit" class="btn-full">چوونەژوورەوە ←</button>
        </form>

        <div class="divider">یان</div>

        <div class="auth-link">
            هەژمارت نییە؟ <a href="{{ route('portal.register') }}">ئێستا تۆمار بکە</a>
        </div>
    </div>
</div>
@endsection
