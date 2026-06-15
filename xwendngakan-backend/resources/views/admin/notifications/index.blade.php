@extends('admin.layouts.app')

@section('title', 'نۆتیفیکەیشن')

@section('content')

<div class="page-header">
    <div>
        <h1>ناردنی نۆتیفیکەیشن</h1>
        <div class="breadcrumb">ناردنی نۆتیفیکەیشنی گشتی (Push Notification) بۆ مۆبایلی هەموو بەکارهێنەران</div>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.notifications.broadcast') }}" method="POST">
        @csrf

        <div class="form-group">
            <label class="form-label" for="title">سەردێڕی نۆتیفیکەیشن <span class="required">*</span></label>
            <input type="text" name="title" id="title" class="form-control" placeholder="بۆ نموونە: ئاگادارییەکی گرنگ" required maxlength="255">
        </div>

        <div class="form-group">
            <label class="form-label" for="message">دەقی نۆتیفیکەیشن <span class="required">*</span></label>
            <textarea name="message" id="message" class="form-control" rows="5" placeholder="دەقی تەواوی ئاگادارییەکە لێرە بنووسە..." required></textarea>
        </div>

        <div class="alert alert-warning mb-4">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>ئەم نۆتیفیکەیشنە دەگاتە دەستی <b>هەموو</b> ئەو بەکارهێنەرانەی مۆبایل ئەپەکەیان دابەزاندووە و نۆتیفیکەیشنیان چالاکە.</div>
        </div>

        <div class="d-flex gap-2" style="justify-content: flex-end;">
            <button type="reset" class="btn btn-ghost">پاشگەزبوونەوە</button>
            <button type="submit" class="btn btn-primary" onclick="return confirm('دڵنیایت لە ناردنی ئەم نۆتیفیکەیشنە بۆ هەموو مۆبایلەکان؟')">
                بڵاوکردنەوەی گشتی
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-right: 8px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </button>
        </div>
    </form>
</div>

@endsection
