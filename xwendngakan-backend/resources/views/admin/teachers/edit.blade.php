@extends('admin.layouts.app')

@section('title', 'دەستکاریکردنی مامۆستا')

@section('content')

<div class="page-header">
    <div>
        <h1>دەستکاریکردنی مامۆستا</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.teachers.index') }}">مامۆستاکان</a> / {{ $teacher->name }}
        </div>
    </div>
</div>

<div class="card" style="max-width: 800px;">
    <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label class="form-label">ناوی سیانی <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $teacher->name) }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">تەلەفۆن <span class="required">*</span></label>
                <input type="text" name="phone" class="form-control" dir="ltr" value="{{ old('phone', $teacher->phone) }}" required>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label class="form-label">بابەت / پسپۆڕی <span class="required">*</span></label>
                <input type="text" name="subject" class="form-control" value="{{ old('subject', $teacher->subject) }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">شار <span class="required">*</span></label>
                <input type="text" name="city" class="form-control" value="{{ old('city', $teacher->city) }}" required>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label class="form-label">جۆری مامۆستا <span class="required">*</span></label>
                <select name="type" class="form-control" required>
                    <option value="school" {{ old('type', $teacher->type) == 'school' ? 'selected' : '' }}>قوتابخانە (بنەڕەتی / ئامادەیی)</option>
                    <option value="university" {{ old('type', $teacher->type) == 'university' ? 'selected' : '' }}>زانکۆ و پەیمانگا</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">پلەی زانستی / ئاست</label>
                <input type="text" name="level" class="form-control" value="{{ old('level', $teacher->level) }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">قوتابخانەکان / شوێنی کار</label>
            <textarea name="schools" class="form-control" rows="3">{{ old('schools', $teacher->schools) }}</textarea>
            <div class="form-hint">بە کۆما (,) جیایان بکەرەوە</div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label class="form-label">ئەزموون (ساڵ)</label>
                <input type="number" name="experience_years" class="form-control" value="{{ old('experience_years', $teacher->experience_years) }}" min="0" max="60">
            </div>

            <div class="form-group">
                <label class="form-label">کرێی کاتژمێری (IQD)</label>
                <input type="number" name="hourly_rate" class="form-control" value="{{ old('hourly_rate', $teacher->hourly_rate) }}" min="0">
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label class="form-label">لینکی ڤیدیۆ (YouTube)</label>
                <input type="url" name="video_url" class="form-control" value="{{ old('video_url', $teacher->video_url) }}" dir="ltr">
            </div>

            <div class="form-group">
                <label class="form-label">لینکی فەیسبوک</label>
                <input type="url" name="facebook_url" class="form-control" value="{{ old('facebook_url', $teacher->facebook_url) }}" dir="ltr">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">دەربارە / پێناسە</label>
            <textarea name="bio" class="form-control" rows="4">{{ old('bio', $teacher->bio) }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">وێنەی پرۆفایل</label>
            <input type="file" name="photo" class="form-control image-upload-input" accept="image/*" data-preview="photo-preview">
            
            @php
                $previewUrl = '#';
                if($teacher->photo) {
                    $previewUrl = Str::startsWith($teacher->photo, ['http://', 'https://', '/']) 
                        ? (Str::startsWith($teacher->photo, '/') && !Str::startsWith($teacher->photo, '/storage/') ? '/storage' . $teacher->photo : $teacher->photo) 
                        : asset('storage/' . $teacher->photo);
                }
            @endphp
            <img id="photo-preview" class="img-preview" src="{{ $previewUrl }}" style="display: {{ $teacher->photo ? 'block' : 'none' }}; max-width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border:2px solid var(--border); background:var(--bg-base);">
        </div>

        <div class="form-group form-check mb-4 mt-4">
            <div class="form-toggle">
                <input type="checkbox" name="is_approved" id="is_approved" value="1" {{ old('is_approved', $teacher->is_approved) ? 'checked' : '' }}>
                <div class="form-toggle-slider"></div>
            </div>
            <label class="form-label" for="is_approved" style="margin: 0;">پەسەندکراوە (دەردەکەوێت لە ئەپڵیکەیشن)</label>
        </div>

        <div class="d-flex gap-2" style="justify-content: flex-end; margin-top: 32px; border-top: 1px solid var(--border); padding-top: 24px;">
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-ghost">پاشگەزبوونەوە</a>
            <button type="submit" class="btn btn-primary">
                نوێکردنەوە
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-right:8px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </button>
        </div>
    </form>
</div>

@endsection
