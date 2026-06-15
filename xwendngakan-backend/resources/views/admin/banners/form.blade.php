@extends('admin.layouts.app')

@section('title', $banner ? 'دەستکاریکردنی بانەر' : 'زیادکردنی بانەر')

@section('content')

<div class="page-header">
    <div>
        <h1>{{ $banner ? 'دەستکاریکردنی بانەر' : 'بانەری نوێ' }}</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.banners.index') }}">بانەرەکان</a> / {{ $banner ? 'دەستکاری' : 'نوێ' }}
        </div>
    </div>
</div>

<div class="card" style="max-width: 800px;">
    <form action="{{ $banner ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($banner) @method('PUT') @endif

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label class="form-label">سەردێڕ <span class="required">*</span></label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $banner?->title) }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">ژێر-سەردێڕ</label>
                <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $banner?->subtitle) }}">
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label class="form-label">لینک</label>
                <input type="url" name="url" class="form-control" dir="ltr" value="{{ old('url', $banner?->url) }}">
            </div>
            
            <div class="form-group">
                <label class="form-label">تاگ (نموونە: نوێ، گرنگ)</label>
                <input type="text" name="tag" class="form-control" value="{{ old('tag', $banner?->tag) }}">
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px;">
            <div class="form-group">
                <label class="form-label">ڕەنگی دەستپێک (Gradient Start)</label>
                <input type="color" name="color_start" class="form-control" style="height:44px; padding:4px;" value="{{ old('color_start', $banner?->color_start ?? '#2563eb') }}">
            </div>
            
            <div class="form-group">
                <label class="form-label">ڕەنگی کۆتایی (Gradient End)</label>
                <input type="color" name="color_end" class="form-control" style="height:44px; padding:4px;" value="{{ old('color_end', $banner?->color_end ?? '#4f46e5') }}">
            </div>

            <div class="form-group">
                <label class="form-label">ڕیزبەندی</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $banner?->sort_order ?? 0) }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">وێنەی بانەر @if(!$banner) <span class="required">*</span> @endif</label>
            <input type="file" name="image" class="form-control image-upload-input" accept="image/*" data-preview="img-preview" {{ !$banner ? 'required' : '' }}>
            <div class="form-hint">پێشنیار دەکرێت وێنەکە بە شێوازی PNG و بێ باکگراوند (Transparent) بێت</div>
            
            <img id="img-preview" class="img-preview" src="{{ $banner?->image ? $banner->image : '#' }}" style="display: {{ $banner?->image ? 'block' : 'none' }}; max-width: 200px; height: 120px; object-fit: contain; background: #333; padding: 10px;">
        </div>

        <div class="form-group form-check mb-4">
            <div class="form-toggle">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $banner?->is_active ?? true) ? 'checked' : '' }}>
                <div class="form-toggle-slider"></div>
            </div>
            <label class="form-label" for="is_active" style="margin: 0;">چالاکە</label>
        </div>

        <div class="d-flex gap-2" style="justify-content: flex-end; margin-top: 32px;">
            <a href="{{ route('admin.banners.index') }}" class="btn btn-ghost">پاشگەزبوونەوە</a>
            <button type="submit" class="btn btn-primary">
                {{ $banner ? 'نوێکردنەوە' : 'پاشەکەوتکردن' }}
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-right:8px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </button>
        </div>
    </form>
</div>

@endsection
