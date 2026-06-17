@extends('admin.layouts.app')

@section('title', 'خوێندنگاکان')

@section('content')

<style>
/* ══════════════════════════════════════════════
   Institutions Index – Responsive Styles
══════════════════════════════════════════════ */

/* Card Grid (shown on mobile/tablet) */
.inst-card-grid {
    display: none;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    padding: 4px 0 16px;
}

.inst-card {
    background: var(--card-bg, #1e2330);
    border: 1px solid var(--border, #2e3448);
    border-radius: 14px;
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
    display: flex;
    flex-direction: column;
}

.inst-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 28px rgba(0,0,0,.35);
}

.inst-card-cover {
    position: relative;
    width: 100%;
    height: 130px;
    background: var(--border, #2e3448);
    overflow: hidden;
}

.inst-card-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.inst-card-cover-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #2a3250 0%, #1e2642 100%);
    color: var(--text-muted, #6b7ea0);
    font-size: 48px;
    opacity: .5;
}

.inst-card-logo-wrap {
    position: absolute;
    bottom: -22px;
    right: 16px;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 3px solid var(--card-bg, #1e2330);
    overflow: hidden;
    background: var(--border, #2e3448);
    box-shadow: 0 2px 8px rgba(0,0,0,.4);
}

.inst-card-logo-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.inst-card-logo-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary, #6366f1), #8b5cf6);
    color: #fff;
    font-size: 18px;
    font-weight: 700;
}

.inst-card-body {
    padding: 32px 16px 14px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.inst-card-name {
    font-weight: 700;
    font-size: 15px;
    color: var(--text, #e2e8f0);
    line-height: 1.3;
}

.inst-card-sub {
    font-size: 12px;
    color: var(--text-muted, #6b7ea0);
    margin-top: -4px;
}

.inst-card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 4px;
}

.inst-card-actions {
    display: flex;
    gap: 8px;
    align-items: center;
    padding: 12px 16px;
    border-top: 1px solid var(--border, #2e3448);
    margin-top: auto;
    justify-content: flex-end;
}

/* Table (desktop) */
.inst-table-wrap {
    display: block;
}

/* Desktop logo in table – bigger */
.table-logo {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--border, #2e3448);
}

.table-logo-placeholder {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary, #6366f1), #8b5cf6);
    color: #fff;
    font-size: 16px;
    font-weight: 700;
}

/* ── Responsive breakpoints ── */
@media (max-width: 900px) {
    .inst-table-wrap  { display: none; }
    .inst-card-grid   { display: grid; }
}

@media (max-width: 640px) {
    .inst-card-grid {
        grid-template-columns: 1fr;
    }
}

@media (min-width: 600px) and (max-width: 900px) {
    .inst-card-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<div class="page-header">
    <div>
        <h1>خوێندنگاکان</h1>
        <div class="breadcrumb">لیست و بەڕێوەبردنی گشت خوێندنگا و پەیمانگاکان</div>
    </div>
    <a href="{{ route('admin.institutions.create') }}" class="btn btn-primary">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        زیادکردنی نوێ
    </a>
</div>

<div class="card">
    <form method="GET" action="{{ route('admin.institutions.index') }}" class="filter-bar">
        <div class="form-group">
            <label class="form-label">گەڕان</label>
            <div class="search-input-wrap">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" class="form-control" placeholder="ناو، شار، تەلەفۆن..." value="{{ request('search') }}">
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">جۆر</label>
            <select name="type" class="form-control" onchange="this.form.submit()">
                <option value="">هەموو جۆرەکان</option>
                @foreach($types as $type)
                    <option value="{{ $type->key }}" {{ request('type') == $type->key ? 'selected' : '' }}>
                        {{ $type->emoji }} {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">دۆخ</label>
            <select name="approved" class="form-control" onchange="this.form.submit()">
                <option value="">هەمووی</option>
                <option value="1" {{ request('approved') === '1' ? 'selected' : '' }}>پەسەندکراو</option>
                <option value="0" {{ request('approved') === '0' ? 'selected' : '' }}>چاوەڕێی پەسەندکردن</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">شار</label>
            <select name="city" class="form-control" onchange="this.form.submit()">
                <option value="">هەموو شارەکان</option>
                @foreach($cities as $city)
                    <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                @endforeach
            </select>
        </div>

        @if(request()->anyFilled(['search', 'type', 'approved', 'city']))
            <div class="form-group" style="flex: 0;">
                <a href="{{ route('admin.institutions.index') }}" class="btn btn-ghost" title="پاککردنەوەی فلتەرەکان" style="height: 42.5px;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            </div>
        @endif
    </form>

    {{-- ════════════════════════════════════════
         DESKTOP TABLE
    ════════════════════════════════════════ --}}
    <div class="inst-table-wrap table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:60px">لۆگۆ</th>
                    <th>ناوی دامەزراوە</th>
                    <th>جۆر</th>
                    <th>شار</th>
                    <th>تەلەفۆن</th>
                    <th>سەردانیکەران</th>
                    <th>دۆخ</th>
                    <th style="text-align:left;">کردارەکان</th>
                </tr>
            </thead>
            <tbody>
                @forelse($institutions as $inst)
                    <tr>
                        <td>
                            @if($inst->logo)
                                <img src="{{ $inst->logo }}" class="table-logo" alt="{{ $inst->nku }}">
                            @else
                                <div class="table-logo-placeholder">{{ mb_substr($inst->nku, 0, 1) }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">{{ $inst->nku }}</div>
                            <div class="td-muted">{{ $inst->nen }}</div>
                        </td>
                        <td>
                            @php $typeObj = $types->where('key', $inst->type)->first(); @endphp
                            <span class="badge badge-gray">
                                {{ $typeObj ? $typeObj->emoji . ' ' . $typeObj->name : $inst->type }}
                            </span>
                        </td>
                        <td>{{ $inst->city }}</td>
                        <td dir="ltr" style="text-align:right;">{{ $inst->phone ?? '-' }}</td>
                        <td>
                            <span class="badge badge-gray">👁 {{ number_format($inst->views ?? 0) }}</span>
                        </td>
                        <td>
                            @if($inst->approved)
                                <span class="badge badge-success"><svg viewBox="0 0 20 20" fill="currentColor" style="width:12px;height:12px;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> پەسەندکراو</span>
                            @else
                                <span class="badge badge-warning"><svg viewBox="0 0 20 20" fill="currentColor" style="width:12px;height:12px;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg> چاوەڕوان</span>
                            @endif
                        </td>
                        <td style="text-align:left;">
                            <div class="d-flex gap-2" style="justify-content:flex-end;">
                                <form action="{{ route('admin.institutions.toggle', $inst) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $inst->approved ? 'btn-ghost' : 'btn-success' }} btn-xs" title="{{ $inst->approved ? 'لابردنی پەسەند' : 'پەسەندکردن' }}">
                                        @if($inst->approved)
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                    </button>
                                </form>
                                <a href="{{ route('admin.institutions.edit', $inst) }}" class="btn btn-ghost btn-xs" title="دەستکاری">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.institutions.destroy', $inst) }}" method="POST" class="confirm-action" data-confirm="دڵنیایت لە سڕینەوەی ئەم دامەزراوەیە؟">
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
                        <td colspan="8" style="text-align:center; padding:40px; color:var(--text-muted);">
                            <svg style="width:48px;height:48px;margin:0 auto 12px;opacity:0.5;display:block;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            <p>هیچ خوێندنگایەک نەدۆزرایەوە.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ════════════════════════════════════════
         MOBILE / TABLET CARD GRID
    ════════════════════════════════════════ --}}
    <div class="inst-card-grid">
        @forelse($institutions as $inst)
            @php $typeObj = $types->where('key', $inst->type)->first(); @endphp
            <div class="inst-card">

                {{-- Cover image --}}
                <div class="inst-card-cover">
                    @if($inst->img)
                        <img src="{{ $inst->img }}" alt="کەڤەر">
                    @else
                        <div class="inst-card-cover-placeholder">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1"/></svg>
                        </div>
                    @endif

                    {{-- Logo badge over cover --}}
                    <div class="inst-card-logo-wrap">
                        @if($inst->logo)
                            <img src="{{ $inst->logo }}" alt="{{ $inst->nku }}">
                        @else
                            <div class="inst-card-logo-placeholder">{{ mb_substr($inst->nku, 0, 1) }}</div>
                        @endif
                    </div>
                </div>

                {{-- Card body --}}
                <div class="inst-card-body">
                    <div class="inst-card-name">{{ $inst->nku }}</div>
                    @if($inst->nen)
                        <div class="inst-card-sub" dir="ltr">{{ $inst->nen }}</div>
                    @endif

                    <div class="inst-card-meta">
                        @if($typeObj)
                            <span class="badge badge-gray">{{ $typeObj->emoji }} {{ $typeObj->name }}</span>
                        @endif
                        @if($inst->city)
                            <span class="badge badge-gray">📍 {{ $inst->city }}</span>
                        @endif
                        @if($inst->approved)
                            <span class="badge badge-success">✓ پەسەندکراو</span>
                        @else
                            <span class="badge badge-warning">⏳ چاوەڕوان</span>
                        @endif
                    </div>

                    @if($inst->phone)
                        <div class="td-muted" dir="ltr" style="font-size:13px;">📞 {{ $inst->phone }}</div>
                    @endif
                </div>

                {{-- Card actions --}}
                <div class="inst-card-actions">
                    <form action="{{ route('admin.institutions.toggle', $inst) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn {{ $inst->approved ? 'btn-ghost' : 'btn-success' }} btn-xs" title="{{ $inst->approved ? 'لابردنی پەسەند' : 'پەسەندکردن' }}">
                            @if($inst->approved)
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @else
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </button>
                    </form>
                    <a href="{{ route('admin.institutions.edit', $inst) }}" class="btn btn-ghost btn-xs" title="دەستکاری">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    <form action="{{ route('admin.institutions.destroy', $inst) }}" method="POST" class="confirm-action" data-confirm="دڵنیایت لە سڕینەوەی ئەم دامەزراوەیە؟">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-xs" title="سڕینەوە">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div style="grid-column:1/-1; text-align:center; padding:48px 0; color:var(--text-muted);">
                <svg style="width:56px;height:56px;margin:0 auto 14px;opacity:0.4;display:block;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                <p>هیچ خوێندنگایەک نەدۆزرایەوە.</p>
            </div>
        @endforelse
    </div>

    {{ $institutions->links('pagination::bootstrap-4') }}
</div>

@endsection
