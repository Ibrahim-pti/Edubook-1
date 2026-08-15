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

<form id="inst-edit-form" action="{{ route('admin.institutions.update', $institution) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="inst-form-grid">
        
        <!-- Main Details -->
        <div class="card">
            <h3 class="fw-bold mb-4" style="border-bottom: 1px solid var(--border); padding-bottom: 12px;">زانیارییە سەرەکییەکان</h3>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group">
                    <label class="form-label">ناو (کوردی سۆرانی) <span class="required">*</span></label>
                    <input type="text" name="nku" class="form-control" value="{{ old('nku', $institution->nku) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">ناو (کوردی بادینی)</label>
                    <input type="text" name="nkbd" class="form-control" value="{{ old('nkbd', $institution->nkbd) }}">
                </div>
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
                <div id="editor-desc" class="quill-editor">{!! \App\Support\HtmlSanitizer::clean(old('desc', $institution->desc)) !!}</div>
                <textarea name="desc" id="desc" style="display:none;">{{ \App\Support\HtmlSanitizer::clean(old('desc', $institution->desc)) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">دەربارە (کوردی بادینی)</label>
                <div id="editor-desc_kbd" class="quill-editor">{!! \App\Support\HtmlSanitizer::clean(old('desc_kbd', $institution->desc_kbd)) !!}</div>
                <textarea name="desc_kbd" id="desc_kbd" style="display:none;">{{ \App\Support\HtmlSanitizer::clean(old('desc_kbd', $institution->desc_kbd)) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">دەربارە (عەرەبی)</label>
                <div id="editor-desc_ar" class="quill-editor">{!! \App\Support\HtmlSanitizer::clean(old('desc_ar', $institution->desc_ar)) !!}</div>
                <textarea name="desc_ar" id="desc_ar" style="display:none;">{{ \App\Support\HtmlSanitizer::clean(old('desc_ar', $institution->desc_ar)) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">دەربارە (ئینگلیزی)</label>
                <div id="editor-desc_en" class="quill-editor" dir="ltr">{!! \App\Support\HtmlSanitizer::clean(old('desc_en', $institution->desc_en)) !!}</div>
                <textarea name="desc_en" id="desc_en" style="display:none;">{{ \App\Support\HtmlSanitizer::clean(old('desc_en', $institution->desc_en)) }}</textarea>
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
                    <label class="form-label">وڵات</label>
                    <input type="text" name="country" class="form-control" value="{{ old('country', $institution->country) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">ناونیشان</label>
                    <input type="text" name="addr" class="form-control" value="{{ old('addr', $institution->addr) }}">
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group">
                    <label class="form-label">تەلەفۆن</label>
                    <input type="text" name="phone" class="form-control" dir="ltr" value="{{ old('phone', $institution->phone) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">ئیمەیڵ</label>
                    <input type="email" name="email" class="form-control" dir="ltr" value="{{ old('email', $institution->email) }}">
                </div>
            </div>
            
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group">
                    <label class="form-label">وێبسایت</label>
                    <input type="text" name="web" class="form-control" dir="ltr" value="{{ old('web', $institution->web) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">ناوی بەڕێوەبەر</label>
                    <input type="text" name="manager_name" class="form-control" value="{{ old('manager_name', $institution->manager_name) }}">
                </div>
            </div>

            <div class="card" style="margin-top: 24px; padding: 16px; border: 1px solid var(--border); border-radius: 8px; background: rgba(0,0,0,0.02);">
                <h4 class="fw-bold mb-3" style="font-size: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 8px;">تۆڕە کۆمەڵایەتییەکان</h4>
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px;">
                    <div class="form-group">
                        <label class="form-label">فەیسبووک (Facebook)</label>
                        <input type="text" name="fb" class="form-control" dir="ltr" value="{{ old('fb', $institution->fb) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">ئینستاگرام (Instagram)</label>
                        <input type="text" name="ig" class="form-control" dir="ltr" value="{{ old('ig', $institution->ig) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">تێلیگرام (Telegram)</label>
                        <input type="text" name="tg" class="form-control" dir="ltr" value="{{ old('tg', $institution->tg) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">وەتسئەپ (Whatsapp)</label>
                        <input type="text" name="wa" class="form-control" dir="ltr" value="{{ old('wa', $institution->wa) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">تیکتۆک (Tiktok)</label>
                        <input type="text" name="tk" class="form-control" dir="ltr" value="{{ old('tk', $institution->tk) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">یوتیوب (Youtube)</label>
                        <input type="text" name="yt" class="form-control" dir="ltr" value="{{ old('yt', $institution->yt) }}">
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 24px; padding: 16px; border: 1px solid var(--border); border-radius: 8px; background: rgba(0,0,0,0.02);">
                <h4 class="fw-bold mb-3" style="font-size: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 8px;">کرێی خوێندن و بەشەکان (تەنیا بینین)</h4>
                @php
                    $colleges = json_decode($institution->colleges ?? '[]', true) ?: [];
                    $tuitionPlans = is_array($institution->tuition_plans) ? $institution->tuition_plans : (json_decode($institution->tuition_plans ?? '[]', true) ?: []);
                @endphp
                
                @if(count($colleges) > 0)
                    @foreach($colleges as $college)
                        <div style="background: var(--bg-base); padding: 12px; border-radius: 6px; margin-bottom: 12px; border: 1px solid var(--border);">
                            <h5 style="color: var(--gold); margin-bottom: 10px;">{{ $college['name'] ?? 'کۆلێژ' }}</h5>
                            <table class="table table-sm" style="font-size: 0.85rem; width: 100%; text-align: right;">
                                <thead>
                                    <tr>
                                        <th style="padding: 6px;">بەش</th>
                                        <th style="padding: 6px;">کرێ</th>
                                        <th style="padding: 6px;">داشکاندن</th>
                                        <th style="padding: 6px;">نرخی کۆتایی</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($college['depts'] ?? [] as $dept)
                                    <tr>
                                        <td style="padding: 6px; border-top: 1px solid var(--border);">{{ $dept['name'] ?? '' }}</td>
                                        <td style="padding: 6px; border-top: 1px solid var(--border);">{{ $dept['fee'] ?? '' }}</td>
                                        <td style="padding: 6px; border-top: 1px solid var(--border);">{{ $dept['discount'] ?? '' }}%</td>
                                        <td style="padding: 6px; border-top: 1px solid var(--border);">{{ $dept['final_price'] ?? '' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @elseif(count($tuitionPlans) > 0)
                    <table class="table table-sm" style="font-size: 0.85rem; width: 100%; text-align: right;">
                        <thead>
                            <tr>
                                <th style="padding: 6px;">بەش / قۆناغ</th>
                                <th style="padding: 6px;">کرێ</th>
                                <th style="padding: 6px;">داشکاندن</th>
                                <th style="padding: 6px;">نرخی کۆتایی</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tuitionPlans as $plan)
                            <tr>
                                <td style="padding: 6px; border-top: 1px solid var(--border);">{{ $plan['dept'] ?? '' }}</td>
                                <td style="padding: 6px; border-top: 1px solid var(--border);">{{ $plan['fee'] ?? '' }}</td>
                                <td style="padding: 6px; border-top: 1px solid var(--border);">{{ $plan['discount'] ?? '' }}%</td>
                                <td style="padding: 6px; border-top: 1px solid var(--border);">{{ $plan['final_price'] ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="font-size: 0.85rem; color: var(--text-muted);">هیچ زانیارییەکی بەش یان کرێ تۆمار نەکراوە.</div>
                @endif
            </div>

            <div class="form-group">
                <label class="form-label">شوێنی دامەزراوە لەسەر نەقشە</label>
                <div style="font-size:.8rem;color:var(--text-muted,#6b7280);margin-bottom:8px;">کلیک لەسەر نەقشەکە بکە بۆ دیاریکردنی شوێن، یان ئەددەکان دەستی بنووسە</div>
                <div id="map-picker" style="height:320px;border-radius:12px;border:1px solid var(--border);overflow:hidden;margin-bottom:12px;"></div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div>
                        <label class="form-label" style="font-size:.8rem;">Latitude</label>
                        <input type="text" name="lat" id="lat-input" class="form-control" dir="ltr"
                               value="{{ old('lat', $institution->lat) }}" placeholder="36.1901">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:.8rem;">Longitude</label>
                        <input type="text" name="lng" id="lng-input" class="form-control" dir="ltr"
                               value="{{ old('lng', $institution->lng) }}" placeholder="44.0090">
                    </div>
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

@push('scripts')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
  .ql-toolbar.ql-snow {
    border: 1px solid var(--border-light) !important;
    border-top-left-radius: var(--radius-sm);
    border-top-right-radius: var(--radius-sm);
    background: var(--bg-hover);
    direction: ltr; /* Quill's toolbar is LTR regardless of content direction */
  }
  .ql-container.ql-snow {
    border: 1px solid var(--border-light) !important;
    border-top: none !important;
    border-bottom-left-radius: var(--radius-sm);
    border-bottom-right-radius: var(--radius-sm);
    background: var(--bg-input);
    font-family: inherit;
    font-size: 13.5px;
    color: var(--text-main);
    min-height: 110px;
  }
  .ql-editor { direction: rtl; text-align: right; }
  .ql-editor.ql-blank::before { color: var(--text-faint); font-style: normal; }
  .quill-editor[dir="ltr"] .ql-editor { direction: ltr !important; text-align: left !important; }
  .quill-editor:not([dir="ltr"]) .ql-editor ol,
  .quill-editor:not([dir="ltr"]) .ql-editor ul { padding-left: 0; padding-right: 1.5em; }
  .quill-editor:not([dir="ltr"]) .ql-editor li::before {
      margin-left: 0 !important;
      margin-right: -1.5em !important;
      text-align: right !important;
  }
  .ql-snow .ql-stroke { stroke: var(--text-muted) !important; }
  .ql-snow .ql-fill { fill: var(--text-muted) !important; }
  .ql-snow .ql-picker-label { color: var(--text-muted) !important; }
  .ql-snow .ql-active .ql-stroke { stroke: var(--primary-text) !important; }
  .ql-snow .ql-active .ql-fill { fill: var(--primary-text) !important; }
</style>
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
(function () {
    const FIELDS = ['desc', 'desc_kbd', 'desc_ar', 'desc_en'];
    const editors = {};

    const toolbarOptions = [
        ['bold', 'italic', 'underline'],
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        ['link'],
        ['clean']
    ];

    FIELDS.forEach(function (field) {
        const el = document.getElementById('editor-' + field);
        if (el) {
            editors[field] = new Quill(el, { theme: 'snow', modules: { toolbar: toolbarOptions } });
        }
    });

    // Quill keeps its value in a contenteditable div, so mirror it into the
    // hidden textarea the controller actually reads.
    document.getElementById('inst-edit-form').addEventListener('submit', function () {
        FIELDS.forEach(function (field) {
            if (!editors[field]) return;
            const target = document.getElementById(field);
            // An "empty" Quill still reports <p><br></p>; store nothing instead.
            target.value = editors[field].getText().trim() === ''
                ? ''
                : editors[field].root.innerHTML;
        });
    });
})();
</script>
<script>
(function () {
    const LAT_INPUT = document.getElementById('lat-input');
    const LNG_INPUT = document.getElementById('lng-input');
    const MAP_DIV   = document.getElementById('map-picker');

    // Default center: Erbil
    const defaultLat = parseFloat(LAT_INPUT.value) || 36.1901;
    const defaultLng = parseFloat(LNG_INPUT.value) || 44.0090;
    const hasPin     = LAT_INPUT.value && LNG_INPUT.value;

    function initMap() {
        const center = { lat: defaultLat, lng: defaultLng };

        const map = new google.maps.Map(MAP_DIV, {
            center,
            zoom: hasPin ? 15 : 12,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
        });

        let marker = null;

        if (hasPin) {
            marker = new google.maps.Marker({ position: center, map, draggable: true });
            marker.addListener('dragend', e => {
                LAT_INPUT.value = e.latLng.lat().toFixed(7);
                LNG_INPUT.value = e.latLng.lng().toFixed(7);
            });
        }

        map.addListener('click', e => {
            const lat = e.latLng.lat().toFixed(7);
            const lng = e.latLng.lng().toFixed(7);
            LAT_INPUT.value = lat;
            LNG_INPUT.value = lng;

            if (marker) {
                marker.setPosition(e.latLng);
            } else {
                marker = new google.maps.Marker({ position: e.latLng, map, draggable: true });
                marker.addListener('dragend', ev => {
                    LAT_INPUT.value = ev.latLng.lat().toFixed(7);
                    LNG_INPUT.value = ev.latLng.lng().toFixed(7);
                });
            }
        });

        // Sync manual text input → move marker
        [LAT_INPUT, LNG_INPUT].forEach(input => {
            input.addEventListener('change', () => {
                const lat = parseFloat(LAT_INPUT.value);
                const lng = parseFloat(LNG_INPUT.value);
                if (!isNaN(lat) && !isNaN(lng)) {
                    const pos = new google.maps.LatLng(lat, lng);
                    map.panTo(pos);
                    map.setZoom(16);
                    if (marker) marker.setPosition(pos);
                    else {
                        marker = new google.maps.Marker({ position: pos, map, draggable: true });
                        marker.addListener('dragend', ev => {
                            LAT_INPUT.value = ev.latLng.lat().toFixed(7);
                            LNG_INPUT.value = ev.latLng.lng().toFixed(7);
                        });
                    }
                }
            });
        });
    }

    // Load Google Maps JS API dynamically
    // Replace YOUR_GOOGLE_MAPS_API_KEY with your real key
    const API_KEY = 'AIzaSyAZbZwzBVGQPPJ930JbhdwRwWH4q-yDRsA';
    const script  = document.createElement('script');
    script.src    = `https://maps.googleapis.com/maps/api/js?key=${API_KEY}&callback=initMapPicker`;
    script.async  = true;
    window.initMapPicker = initMap;
    document.head.appendChild(script);
})();
</script>
@endpush
