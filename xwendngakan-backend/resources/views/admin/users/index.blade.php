@extends('admin.layouts.app')

@section('title', 'بەکارهێنەران')

@section('content')

<div class="page-header">
    <div>
        <h1>بەکارهێنەران</h1>
        <div class="breadcrumb">لیست و بەڕێوەبردنی ئەکاونتەکانی سیستەم (مۆبایل و پۆرتال)</div>
    </div>
</div>

<div class="card">
    <form method="GET" action="{{ route('admin.users.index') }}" class="filter-bar">
        <div class="form-group">
            <label class="form-label">گەڕان</label>
            <div class="search-input-wrap">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" class="form-control" placeholder="ناو، ئیمەیڵ یان ژمارەی مۆبایل..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">جۆری بەکارهێنەر</label>
            <select name="user_type" class="form-control" onchange="this.form.submit()">
                <option value="">هەمووی</option>
                <option value="portal" {{ request('user_type') === 'portal' ? 'selected' : '' }}>خاوەن خوێندنگا (پۆرتال)</option>
                <option value="mobile" {{ request('user_type') === 'mobile' ? 'selected' : '' }}>قوتابی/کەسوکار (مۆبایل ئەپ)</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">دۆخ</label>
            <select name="approved" class="form-control" onchange="this.form.submit()">
                <option value="">هەمووی</option>
                <option value="1" {{ request('approved') === '1' ? 'selected' : '' }}>چالاککراو</option>
                <option value="0" {{ request('approved') === '0' ? 'selected' : '' }}>ڕاگیراو / چاوەڕوان</option>
            </select>
        </div>

        @if(request()->anyFilled(['search', 'user_type', 'approved']))
            <div class="form-group" style="flex: 0;">
                <a href="{{ route('admin.users.index') }}" class="btn btn-ghost" title="پاککردنەوە" style="height: 42.5px;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            </div>
        @endif
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ناو</th>
                    <th>ئیمەیڵ</th>
                    <th>ژمارەی مۆبایل</th>
                    <th>جۆر</th>
                    <th>بەرواری تۆماربوون</th>
                    <th>دۆخ</th>
                    <th style="text-align:left;">کردارەکان</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-center gap-2">
                                <div class="avatar-placeholder">{{ mb_substr($user->name, 0, 1) }}</div>
                                <span class="fw-bold">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td dir="ltr" style="text-align:right;">{{ $user->email }}</td>
                        <td dir="ltr" style="text-align:right;">
                            @if($user->phone)
                                <a href="tel:{{ $user->phone }}" class="fw-bold">{{ $user->phone }}</a>
                            @else
                                <span class="td-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($user->user_type == 'portal')
                                <span class="badge badge-info"><svg viewBox="0 0 20 20" fill="currentColor" style="width:12px;height:12px;"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg> پۆرتال</span>
                            @else
                                <span class="badge badge-primary"><svg viewBox="0 0 20 20" fill="currentColor" style="width:12px;height:12px;"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg> مۆبایل</span>
                            @endif
                        </td>
                        <td class="td-muted">{{ $user->created_at->format('Y/m/d H:i') }}</td>
                        <td>
                            @if($user->is_approved)
                                <span class="badge badge-success">چالاک</span>
                            @else
                                <span class="badge badge-danger">ڕاگیراو</span>
                            @endif
                        </td>
                        <td style="text-align:left;">
                            <div class="d-flex gap-2" style="justify-content:flex-end;">
                                <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="confirm-action" data-confirm="{{ $user->is_approved ? 'دڵنیایت لە ڕاگرتنی ئەم ئەکاونتە؟' : 'دڵنیایت لە چالاککردنی ئەم ئەکاونتە؟' }}">
                                    @csrf
                                    <button type="submit" class="btn {{ $user->is_approved ? 'btn-ghost' : 'btn-success' }} btn-xs" title="{{ $user->is_approved ? 'ڕاگرتن' : 'چالاککردن' }}">
                                        @if($user->is_approved)
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </button>
                                </form>
                                <button type="button" class="btn btn-info btn-xs notify-btn" data-id="{{ $user->id }}" data-name="{{ $user->name }}" title="ناردنی نۆتیفیکەیشن">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                </button>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="confirm-action" data-confirm="دڵنیایت لە سڕینەوەی ئەم بەکارهێنەرە؟ ئەم کردارە هەڵناوەشێتەوە.">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs" title="سڕینەوە">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:40px; color:var(--text-muted);">هیچ بەکارهێنەرێک نەدۆزرایەوە.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $users->links('pagination::bootstrap-4') }}
</div>

<!-- Notify Modal -->
<div class="modal-overlay" id="notify-modal" style="display: none;">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">ناردنی نۆتیفیکەیشن بۆ <span id="notify-name" class="text-primary"></span></h3>
            <button class="modal-close" id="close-notify-modal"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <form id="notify-form" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">سەردێڕ <span class="required">*</span></label>
                <input type="text" name="title" class="form-control" required maxlength="255">
            </div>
            <div class="form-group">
                <label class="form-label">دەق <span class="required">*</span></label>
                <textarea name="message" class="form-control" rows="4" required></textarea>
            </div>
            <div class="d-flex gap-2" style="justify-content: flex-end; margin-top: 24px;">
                <button type="button" class="btn btn-ghost" id="cancel-notify">پاشگەزبوونەوە</button>
                <button type="submit" class="btn btn-primary">ناردن</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('notify-modal');
    const form = document.getElementById('notify-form');
    const nameSpan = document.getElementById('notify-name');
    const btns = document.querySelectorAll('.notify-btn');
    
    const closeBtn = document.getElementById('close-notify-modal');
    const cancelBtn = document.getElementById('cancel-notify');

    function closeModal() { modal.style.display = 'none'; }

    btns.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            nameSpan.textContent = this.dataset.name;
            form.action = `/admin/users/${id}/notify`;
            modal.style.display = 'flex';
        });
    });

    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
});
</script>

@endsection
