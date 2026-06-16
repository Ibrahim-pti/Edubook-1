@extends('admin.layouts.app')

@section('title', 'دەستکاریکردنی خوێندنگا')

@section('content')

<style>
.inst-form-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
}
@media (max-width: 900px) {
    .inst-form-grid {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 600px) {
    .inst-form-grid > div > .card {
        margin-bottom: 16px;
    }
}
</style>

<div class="page-header">
    <div>
        <h1>دەستکاریکردنی خوێندنگا</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.institutions.index') }}">خوێندنگاکان</a> / {{ $institution->nku }}
        </div>
    </div>
</div>

<form action="{{ route('admin.institutions.update', $institution) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="inst-form-grid">
        
        <!-- Main Details -->
        <div class="card">
            <h3 class="fw-bold mb-4" style="border-bottom: 1px solid var(--border); padding-bottom: 12px;">زانیارییە سەرەکییەکان</h3>

            <div class="form-group">
                <label class="form-label">ناو (کوردی) <span class="required">*</span></label>
                <input type="text" name="nku" class="form-control" value="{{ old('nku', $institution->nku) }}" required>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group">
                    <label class="form-label">ناو (ئینگلیزی)</label>
                    <input type="text" name="nen" class="form-control" dir="ltr" value="{{ old('nen', $institution->nen) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">ناو (عەرەبی)</label>
                    <input type="text" name="nar" class="form-control" value="{{ old('nar', $institution->nar) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">دەربارە / پێناسە (کوردی سۆرانی)</label>
                <textarea name="desc" class="form-control" rows="4">{{ old('desc', $institution->desc) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">دەربارە (کوردی بادینی)</label>
                <textarea name="desc_kbd" class="form-control" rows="4">{{ old('desc_kbd', $institution->desc_kbd) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">دەربارە (عەرەبی)</label>
                <textarea name="desc_ar" class="form-control" rows="4" dir="rtl">{{ old('desc_ar', $institution->desc_ar) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">دەربارە (ئینگلیزی)</label>
                <textarea name="desc_en" class="form-control" rows="4" dir="ltr">{{ old('desc_en', $institution->desc_en) }}</textarea>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group">
                    <label class="form-label">جۆری دامەزراوە <span class="required">*</span></label>
                    <select name="type" class="form-control" required>
                        <option value="">هەڵبژێرە...</option>
                        @foreach($types as $t)
                            <option value="{{ $t->key }}" {{ old('type', $institution->type) == $t->key ? 'selected' : '' }}>
                                {{ $t->emoji }} {{ $t->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">شار <span class="required">*</span></label>
                    <select name="city" class="form-control" required>
                        <option value="">هەڵبژێرە...</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ old('city', $institution->city) == $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group">
                    <label class="form-label">تەلەفۆن</label>
                    <input type="text" name="phone" class="form-control" dir="ltr" value="{{ old('phone', $institution->phone) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">ناوی بەڕێوەبەر</label>
                    <input type="text" name="manager_name" class="form-control" value="{{ old('manager_name', $institution->manager_name) }}">
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group">
                    <label class="form-label">هێڵی پانی (Latitude)</label>
                    <input type="text" name="lat" class="form-control" dir="ltr" value="{{ old('lat', $institution->lat) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">هێڵی درێژی (Longitude)</label>
                    <input type="text" name="lng" class="form-control" dir="ltr" value="{{ old('lng', $institution->lng) }}">
                </div>
            </div>
        </div>

        <!-- Sidebar Details -->
        <div>
            <div class="card mb-4">
                <h3 class="fw-bold mb-4" style="border-bottom: 1px solid var(--border); padding-bottom: 12px;">میدیا</h3>

                <div class="form-group">
                    <label class="form-label">لۆگۆ</label>
                    <input type="file" name="logo" class="form-control image-upload-input" accept="image/*" data-preview="logo-preview">
                    <img id="logo-preview" class="img-preview" src="{{ $institution->logo ?? '#' }}" style="display: {{ $institution->logo ? 'block' : 'none' }}; max-width: 100px; height: 100px; border-radius: 50%; object-fit: contain; border:1px solid var(--border); background:var(--bg-base);">
                </div>

                <div class="form-group">
                    <label class="form-label">کەڤەر</label>
                    <input type="file" name="cover" class="form-control image-upload-input" accept="image/*" data-preview="cover-preview">
                    <img id="cover-preview" class="img-preview" src="{{ $institution->img ?? '#' }}" style="display: {{ $institution->img ? 'block' : 'none' }}; height: 100px; width: 100%; object-fit: cover; border-radius: 8px; margin-top: 8px;">
                </div>
            </div>

            <div class="card">
                <h3 class="fw-bold mb-4" style="border-bottom: 1px solid var(--border); padding-bottom: 12px;">دۆخ</h3>

                <div class="form-group form-check">
                    <div class="form-toggle">
                        <input type="checkbox" name="approved" id="approved" value="1" {{ old('approved', $institution->approved) ? 'checked' : '' }}>
                        <div class="form-toggle-slider"></div>
                    </div>
                    <label class="form-label" for="approved" style="margin: 0;">پەسەندکراوە</label>
                </div>

                <div class="form-group form-check">
                    <div class="form-toggle">
                        <input type="checkbox" name="is_premium" id="is_premium" value="1" {{ old('is_premium', $institution->is_premium) ? 'checked' : '' }}>
                        <div class="form-toggle-slider"></div>
                    </div>
                    <label class="form-label" for="is_premium" style="margin: 0; color:var(--primary-text);">ئەکاونتی پریمێم</label>
                </div>
                
                <div class="form-group mt-4">
                    <label class="form-label">لینک بە بەکارهێنەر (بۆ بەڕێوەبردنی پۆرتال)</label>
                    <select name="user_id" class="form-control">
                        <option value="">بێ بەکارهێنەر (تەنیا نیشاندان)</option>
                        @foreach(\App\Models\User::where('user_type', 'portal')->get() as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $institution->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4 pt-4" style="border-top: 1px solid var(--border);">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        نوێکردنەوە
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-right:8px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
