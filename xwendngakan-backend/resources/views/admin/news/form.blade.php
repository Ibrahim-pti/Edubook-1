@extends('admin.layouts.app')

@section('title', $news ? 'دەستکاریکردنی هەواڵ' : 'زیادکردنی هەواڵ')

@section('content')

<div class="page-header">
    <div>
        <h1>{{ $news ? 'دەستکاریکردنی هەواڵ' : 'هەواڵی نوێ' }}</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.news.index') }}">هەواڵەکان</a> / {{ $news ? 'دەستکاری' : 'نوێ' }}
        </div>
    </div>
</div>

<div class="card" style="max-width: 800px;">
    <form action="{{ $news ? route('admin.news.update', $news) : route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($news) @method('PUT') @endif

        <div class="form-group">
            <label class="form-label">سەردێڕ <span class="required">*</span></label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $news?->title) }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">دەق <span class="required">*</span></label>
            <textarea name="content" class="form-control" rows="8" required>{{ old('content', $news?->content) }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">وێنە @if(!$news) <span class="required">*</span> @endif</label>
            <input type="file" name="image" class="form-control image-upload-input" accept="image/*" data-preview="img-preview" {{ !$news ? 'required' : '' }}>
            <div class="form-hint">بەرزترین قەبارە: 10MB</div>
            
            <img id="img-preview" class="img-preview" src="{{ $news?->image ? $news->image : '#' }}" style="display: {{ $news?->image ? 'block' : 'none' }}; max-width: 300px; height: 200px;">
        </div>

        <div class="form-group form-check mb-4">
            <div class="form-toggle">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $news?->is_active ?? true) ? 'checked' : '' }}>
                <div class="form-toggle-slider"></div>
            </div>
            <label class="form-label" for="is_active" style="margin: 0;">چالاکە (لە ئەپڵیکەیشن دەردەکەوێت)</label>
        </div>

        <div class="d-flex gap-2" style="justify-content: flex-end; margin-top: 32px;">
            <a href="{{ route('admin.news.index') }}" class="btn btn-ghost">پاشگەزبوونەوە</a>
            <button type="submit" class="btn btn-primary">
                {{ $news ? 'نوێکردنەوە' : 'پاشەکەوتکردن' }}
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-right:8px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </button>
        </div>
    </form>
</div>

@endsection
