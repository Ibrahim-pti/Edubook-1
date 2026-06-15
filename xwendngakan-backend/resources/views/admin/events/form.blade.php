@extends('admin.layouts.app')

@section('title', $event ? 'دەستکاریکردنی ڕووداو' : 'زیادکردنی ڕووداو')

@section('content')

<div class="page-header">
    <div>
        <h1>{{ $event ? 'دەستکاریکردنی ڕووداو' : 'ڕووداوی نوێ' }}</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.events.index') }}">ڕووداوەکان</a> / {{ $event ? 'دەستکاری' : 'نوێ' }}
        </div>
    </div>
</div>

<div class="card" style="max-width: 800px;">
    <form action="{{ $event ? route('admin.events.update', $event) : route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($event) @method('PUT') @endif

        <div class="form-group">
            <label class="form-label">سەردێڕ <span class="required">*</span></label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $event?->title) }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">دەق / زانیاری زیاتر</label>
            <textarea name="description" class="form-control" rows="5">{{ old('description', $event?->description) }}</textarea>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px;">
            <div class="form-group">
                <label class="form-label">ڕێکەوتی دەستپێک <span class="required">*</span></label>
                <input type="date" name="start_date" class="form-control" dir="ltr" value="{{ old('start_date', $event?->start_date?->format('Y-m-d')) }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">ڕێکەوتی کۆتایی</label>
                <input type="date" name="end_date" class="form-control" dir="ltr" value="{{ old('end_date', $event?->end_date?->format('Y-m-d')) }}">
            </div>

            <div class="form-group">
                <label class="form-label">ڕەنگ <span class="required">*</span></label>
                <input type="color" name="color" class="form-control" style="height:44px; padding:4px;" value="{{ old('color', $event?->color ?? '#3b82f6') }}" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">وێنە / لۆگۆی ڕووداو</label>
            <input type="file" name="image" class="form-control image-upload-input" accept="image/*" data-preview="img-preview">
            
            <img id="img-preview" class="img-preview" src="{{ $event?->image ? $event->image : '#' }}" style="display: {{ $event?->image ? 'block' : 'none' }}; max-width: 200px; height: 120px; object-fit: cover;">
        </div>

        <div class="d-flex gap-2" style="justify-content: flex-end; margin-top: 32px;">
            <a href="{{ route('admin.events.index') }}" class="btn btn-ghost">پاشگەزبوونەوە</a>
            <button type="submit" class="btn btn-primary">
                {{ $event ? 'نوێکردنەوە' : 'پاشەکەوتکردن' }}
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-right:8px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </button>
        </div>
    </form>
</div>

@endsection
