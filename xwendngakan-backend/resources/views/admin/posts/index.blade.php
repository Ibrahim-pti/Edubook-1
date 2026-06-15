@extends('admin.layouts.app')

@section('title', 'پۆستەکان')

@section('content')

<div class="page-header">
    <div>
        <h1>پۆستەکان</h1>
        <div class="breadcrumb">بەڕێوەبردنی پۆستی خوێندنگاکان</div>
    </div>
</div>

<div class="card">
    <form method="GET" action="{{ route('admin.posts.index') }}" class="filter-bar">
        <div class="form-group">
            <label class="form-label">گەڕان</label>
            <div class="search-input-wrap">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" class="form-control" placeholder="گەڕان بەدوای سەردێڕ..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">دۆخ</label>
            <select name="approved" class="form-control" onchange="this.form.submit()">
                <option value="">هەمووی</option>
                <option value="1" {{ request('approved') === '1' ? 'selected' : '' }}>پەسەندکراو</option>
                <option value="0" {{ request('approved') === '0' ? 'selected' : '' }}>چاوەڕێی پەسەندکردن</option>
            </select>
        </div>

        @if(request()->anyFilled(['search', 'approved']))
            <div class="form-group" style="flex: 0;">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-ghost" title="پاککردنەوە" style="height: 42.5px;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            </div>
        @endif
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:60px">وێنە</th>
                    <th>سەردێڕ</th>
                    <th>خوێندنگا</th>
                    <th>بەروار</th>
                    <th>دۆخ</th>
                    <th style="text-align:left;">کردارەکان</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                    <tr>
                        <td>
                            @if($post->image)
                                <img src="{{ $post->image }}" class="avatar" alt="Image" style="border-radius:8px;">
                            @else
                                <div class="avatar-placeholder" style="border-radius:8px;">
                                    <svg style="width:20px;height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">{{ \Illuminate\Support\Str::limit($post->title, 40) }}</div>
                        </td>
                        <td>
                            @if($post->institution)
                                <span class="badge badge-gray">{{ $post->institution->nku }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="td-muted">{{ $post->created_at->format('Y/m/d') }}</td>
                        <td>
                            @if($post->approved)
                                <span class="badge badge-success">پەسەندکراو</span>
                            @else
                                <span class="badge badge-warning">چاوەڕوان</span>
                            @endif
                        </td>
                        <td style="text-align:left;">
                            <div class="d-flex gap-2" style="justify-content:flex-end;">
                                <form action="{{ route('admin.posts.toggle', $post) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $post->approved ? 'btn-ghost' : 'btn-success' }} btn-xs" title="{{ $post->approved ? 'لابردنی پەسەند' : 'پەسەندکردن' }}">
                                        @if($post->approved)
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                    </button>
                                </form>
                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="confirm-action" data-confirm="دڵنیایت لە سڕینەوەی ئەم پۆستە؟">
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
                        <td colspan="6" style="text-align:center; padding:40px; color:var(--text-muted);">هیچ پۆستێک نەدۆزرایەوە.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $posts->links('pagination::bootstrap-4') }}
</div>

@endsection
