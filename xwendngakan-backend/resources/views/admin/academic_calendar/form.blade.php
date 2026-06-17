@extends('admin.layouts.app')

@section('title', $event ? 'دەستکاریکردنی ڕووداو' : 'زیادکردنی ڕووداوی نوێ')

@section('content')

<div class="page-header">
    <div>
        <h1>{{ $event ? 'دەستکاریکردنی ڕووداو' : 'زیادکردنی ڕووداوی نوێ' }}</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.academic-calendar.index') }}">ڕۆژژمێری ئەکادیمی</a> / {{ $event ? $event->title : 'نوێ' }}
        </div>
    </div>
</div>

<div class="card" style="max-width: 800px;">
    <form action="{{ $event ? route('admin.academic-calendar.update', $event) : route('admin.academic-calendar.store') }}" method="POST">
        @csrf
        @if($event)
            @method('PUT')
        @endif

        <div style="display:grid; grid-template-columns:1fr; gap:16px;">
            <div class="form-group">
                <label class="form-label">ناوی ڕووداو / سەردێڕ <span class="required">*</span></label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $event?->title) }}" required placeholder="بۆ نموونە: پشووی جەژنی نەورۆز">
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label class="form-label">بەرواری دەستپێکردن <span class="required">*</span></label>
                <input type="date" name="date" class="form-control" value="{{ old('date', $event?->date?->format('Y-m-d')) }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">ماوە (ڕۆژ) <span class="required">*</span></label>
                <input type="number" name="duration_days" class="form-control" value="{{ old('duration_days', $event?->duration_days ?? 1) }}" min="1" required>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label class="form-label">جۆر / پۆلێن <span class="required">*</span></label>
                <select name="category" class="form-control" required>
                    <option value="holiday" {{ old('category', $event?->category) == 'holiday' ? 'selected' : '' }}>پشوو (Holiday)</option>
                    <option value="exam" {{ old('category', $event?->category) == 'exam' ? 'selected' : '' }}>تاقیکردنەوە یان خوێندن (Exam / School)</option>
                    <option value="deadline" {{ old('category', $event?->category) == 'deadline' ? 'selected' : '' }}>تۆمارکردن یان وادە (Deadline / Registration)</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">ئایکۆن (ناوی ئایکۆنی Flutter)</label>
                <input type="text" name="icon" class="form-control" value="{{ old('icon', $event?->icon ?? 'celebration_rounded') }}" placeholder="بۆ نموونە: celebration_rounded یان school_rounded">
                <div class="form-hint">ناوی ئایکۆنەکە لە Flutter (وەکو: celebration_rounded, school_rounded, assignment_rounded)</div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">ڕوونکردنەوە / وصف</label>
            <textarea name="description" class="form-control" rows="4" placeholder="ڕوونکردنەوەی زیاتر دەربارەی ئەم ڕووداوە بنووسە...">{{ old('description', $event?->description) }}</textarea>
        </div>

        <div class="d-flex gap-2" style="justify-content: flex-end; margin-top: 32px; border-top: 1px solid var(--border); padding-top: 24px;">
            <a href="{{ route('admin.academic-calendar.index') }}" class="btn btn-ghost">پاشگەزبوونەوە</a>
            <button type="submit" class="btn btn-primary">
                {{ $event ? 'نوێکردنەوە' : 'پاشەکەوتکردن' }}
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-right:8px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </button>
        </div>
    </form>
</div>

@endsection
