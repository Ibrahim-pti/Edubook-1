<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>چوونە ژوورەوە - پانێڵی ئەدمین</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="login-page">
        <div class="login-card">
            <div class="login-logo">
                <img src="/images/logo.svg" alt="EduBook - IQ">
                <h1>ئێدوو بووک</h1>
                <p>چوونە ژوورەوە بۆ پانێڵی بەڕێوەبردن</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger" style="margin-bottom: 24px;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">ئیمەیڵ</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password">وشەی نهێنی</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="form-group form-check mb-4">
                    <div class="form-toggle">
                        <input type="checkbox" name="remember" id="remember">
                        <div class="form-toggle-slider"></div>
                    </div>
                    <label class="form-label" for="remember" style="margin: 0; cursor: pointer;">من بەبیر بهێنەرەوە</label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 15px; padding: 12px;">
                    چوونە ژوورەوە
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-right: 8px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>
</body>
</html>
