<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'پانێڵی ئەدمین') - Edu Book</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    @if(session('success'))
        <div id="flash-success" data-message="{{ session('success') }}"></div>
    @endif
    @if(session('error'))
        <div id="flash-error" data-message="{{ session('error') }}"></div>
    @endif

    <div class="modal-overlay" id="sidebar-overlay" style="display: none; z-index: 299;"></div>

    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="admin-sidebar">
            <div class="sidebar-logo">
                <img src="/images/logo.svg" alt="Edu Book">
                <div class="sidebar-logo-text">
                    <span class="logo-name">ئێدوو بووک</span>
                    <span class="logo-sub">پانێڵی ئەدمین</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-group">
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>داشبۆرد</span>
                    </a>
                </div>

                <div class="nav-group-label">بەڕێوەبردن</div>
                
                <a href="{{ route('admin.institutions.index') }}" class="nav-item {{ request()->routeIs('admin.institutions.*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>خوێندنگاکان</span>
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>بەکارهێنەران</span>
                </a>
                
                <a href="{{ route('admin.teachers.index') }}" class="nav-item {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>مامۆستاکان</span>
                </a>
                
                <a href="{{ route('admin.cvs.index') }}" class="nav-item {{ request()->routeIs('admin.cvs.*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>CVکان</span>
                    @php $pendingCvs = \App\Models\Cv::where('is_reviewed', false)->count(); @endphp
                    @if($pendingCvs > 0) <span class="nav-badge">{{ $pendingCvs }}</span> @endif
                </a>

                <div class="nav-group-label">ناوەڕۆک</div>

                <a href="{{ route('admin.posts.index') }}" class="nav-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    <span>پۆستەکان</span>
                </a>

                <a href="{{ route('admin.news.index') }}" class="nav-item {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                    <span>هەواڵەکان</span>
                </a>

                <a href="{{ route('admin.banners.index') }}" class="nav-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>بانەرەکان</span>
                </a>

                <a href="{{ route('admin.events.index') }}" class="nav-item {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>ڕووداوەکان</span>
                </a>

                <a href="{{ route('admin.academic-calendar.index') }}" class="nav-item {{ request()->routeIs('admin.academic-calendar.*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>ڕۆژژمێری ئەکادیمی</span>
                </a>

                <div class="nav-group-label">داواکارییەکان</div>

                <a href="{{ route('admin.inst-requests.index') }}" class="nav-item {{ request()->routeIs('admin.inst-requests.*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>داواکاری خوێندنگا</span>
                    @php $pendingInsts = \App\Models\InstitutionRequest::where('status', 'pending')->count(); @endphp
                    @if($pendingInsts > 0) <span class="nav-badge">{{ $pendingInsts }}</span> @endif
                </a>

                <a href="{{ route('admin.teacher-requests.index') }}" class="nav-item {{ request()->routeIs('admin.teacher-requests.*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>داواکاری مامۆستا</span>
                    @php $pendingTchs = \App\Models\TeacherRequest::where('status', 'pending')->count(); @endphp
                    @if($pendingTchs > 0) <span class="nav-badge">{{ $pendingTchs }}</span> @endif
                </a>

                <div class="nav-group-label">سیستەم</div>

                <a href="{{ route('admin.types.index') }}" class="nav-item {{ request()->routeIs('admin.types.*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    <span>جۆرەکانی خوێندنگا</span>
                </a>

                <a href="{{ route('admin.notifications.index') }}" class="nav-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span>نۆتیفیکەیشن</span>
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-ghost" style="width: 100%;">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        چوونە دەرەوە
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Topbar -->
            <header class="admin-topbar">
                <button class="btn btn-ghost btn-sm" id="sidebar-toggle" style="display: none; padding: 4px;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:24px;height:24px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="topbar-title">@yield('title')</div>
                
                <div class="topbar-user">
                    <span>{{ auth()->guard('web')->user()->name }}</span>
                    <div class="topbar-avatar">
                        {{ mb_substr(auth()->guard('web')->user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <div style="padding-top: 24px;">
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ asset('js/admin.js') }}"></script>
    <style>
        @media (max-width: 900px) {
            #sidebar-toggle { display: block !important; }
            .admin-topbar { padding: 0 16px; margin: -28px -28px 24px -28px; height: 60px; }
        }
    </style>
    @stack('scripts')
</body>
</html>
