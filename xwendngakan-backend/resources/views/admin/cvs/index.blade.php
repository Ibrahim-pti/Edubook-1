@extends('admin.layouts.app')

@section('title', 'سی ڤییەکان')

@section('content')

<div class="page-header">
    <div>
        <h1>CVکان</h1>
        <div class="breadcrumb">لیستی سی ڤی پێشکەشکراوەکان لەلایەن مامۆستایانەوە</div>
    </div>
</div>

<div class="card">
    <form method="GET" action="{{ route('admin.cvs.index') }}" class="filter-bar">
        <div class="form-group">
            <label class="form-label">گەڕان</label>
            <div class="search-input-wrap">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" class="form-control" placeholder="ناو، بوار، تەلەفۆن..." value="{{ request('search') }}">
            </div>
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

        <div class="form-group">
            <label class="form-label">دۆخ</label>
            <select name="reviewed" class="form-control" onchange="this.form.submit()">
                <option value="">هەمووی</option>
                <option value="1" {{ request('reviewed') === '1' ? 'selected' : '' }}>پشکنینکراو</option>
                <option value="0" {{ request('reviewed') === '0' ? 'selected' : '' }}>چاوەڕێی پشکنین</option>
            </select>
        </div>

        @if(request()->anyFilled(['search', 'city', 'reviewed']))
            <div class="form-group" style="flex: 0;">
                <a href="{{ route('admin.cvs.index') }}" class="btn btn-ghost" title="پاککردنەوە" style="height: 42.5px;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            </div>
        @endif
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:50px">وێنە</th>
                    <th>ناو</th>
                    <th>بوار/پیشە</th>
                    <th>ئاستی خوێندن</th>
                    <th>شار</th>
                    <th>دۆخ</th>
                    <th style="text-align:left;">کردارەکان</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cvs as $cv)
                    <tr>
                        <td>
                            @if($cv->photo)
                                <img src="{{ $cv->photo }}" class="avatar" alt="Photo">
                            @else
                                <div class="avatar-placeholder">{{ mb_substr($cv->name, 0, 1) }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">{{ $cv->name }}</div>
                            <div class="td-muted" dir="ltr" style="text-align:right;">{{ $cv->phone }}</div>
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($cv->field, 30) }}</td>
                        <td><span class="badge badge-primary">{{ $cv->education_level }}</span></td>
                        <td>{{ $cv->city }}</td>
                        <td>
                            @if($cv->is_reviewed)
                                <span class="badge badge-success"><svg viewBox="0 0 20 20" fill="currentColor" style="width:12px;height:12px;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> پشکنینکراو</span>
                            @else
                                <span class="badge badge-warning"><svg viewBox="0 0 20 20" fill="currentColor" style="width:12px;height:12px;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg> نەپشکنینکراو</span>
                            @endif
                        </td>
                        <td style="text-align:left;">
                            <div class="d-flex gap-2" style="justify-content:flex-end;">
                                <form action="{{ route('admin.cvs.toggle', $cv) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $cv->is_reviewed ? 'btn-ghost' : 'btn-success' }} btn-xs" title="{{ $cv->is_reviewed ? 'لابردنی پشکنین' : 'پشکنینکراو' }}">
                                        @if($cv->is_reviewed)
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                    </button>
                                </form>
                                <form action="{{ route('admin.cvs.destroy', $cv) }}" method="POST" class="confirm-action" data-confirm="دڵنیایت لە سڕینەوەی ئەم CVیە؟">
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
                        <td colspan="7" style="text-align:center; padding:40px; color:var(--text-muted);">هیچ CVیەک نەدۆزرایەوە.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $cvs->links('pagination::bootstrap-4') }}
</div>

@endsection
