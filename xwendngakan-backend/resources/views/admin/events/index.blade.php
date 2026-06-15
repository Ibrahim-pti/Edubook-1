@extends('admin.layouts.app')

@section('title', 'ڕووداوەکان')

@section('content')

<div class="page-header">
    <div>
        <h1>ڕووداوەکان (Events)</h1>
        <div class="breadcrumb">بەڕێوەبردنی ڕووداوەکانی ساڵنامە</div>
    </div>
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        ڕووداوی نوێ
    </a>
</div>

<div class="card">
    <form method="GET" action="{{ route('admin.events.index') }}" class="filter-bar">
        <div class="form-group">
            <label class="form-label">گەڕان</label>
            <div class="search-input-wrap">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" class="form-control" placeholder="سەردێڕ، دەق..." value="{{ request('search') }}">
            </div>
        </div>

        @if(request()->filled('search'))
            <div class="form-group" style="flex: 0;">
                <a href="{{ route('admin.events.index') }}" class="btn btn-ghost" title="پاککردنەوە" style="height: 42.5px;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            </div>
        @endif
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:70px">وێنە</th>
                    <th>سەردێڕ</th>
                    <th>ڕەنگ</th>
                    <th>ڕێکەوتی دەستپێک</th>
                    <th>ڕێکەوتی کۆتایی</th>
                    <th style="text-align:left;">کردارەکان</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                    <tr>
                        <td>
                            @if($event->image)
                                <img src="{{ $event->image }}" class="img-preview" style="height:50px; width:50px; border-radius:8px; margin:0;" alt="Image">
                            @else
                                <div class="avatar-placeholder" style="border-radius:8px; width:50px; height:50px;">
                                    <svg style="width:20px;height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </td>
                        <td class="fw-bold">{{ $event->title }}</td>
                        <td dir="ltr" style="text-align:right;">
                            <div class="d-flex align-center gap-2" style="justify-content:flex-end;">
                                <div style="width:20px; height:20px; border-radius:4px; border:1px solid var(--border); background:{{ $event->color ?? '#3b82f6' }};"></div>
                                <span class="td-muted text-xs">{{ $event->color ?? '#3b82f6' }}</span>
                            </div>
                        </td>
                        <td class="td-muted" dir="ltr" style="text-align:right;">{{ $event->start_date?->format('Y-m-d') }}</td>
                        <td class="td-muted" dir="ltr" style="text-align:right;">{{ $event->end_date?->format('Y-m-d') ?? '-' }}</td>
                        <td style="text-align:left;">
                            <div class="d-flex gap-2" style="justify-content:flex-end;">
                                <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-ghost btn-xs" title="دەستکاری">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="confirm-action" data-confirm="دڵنیایت لە سڕینەوەی ئەم ڕووداوە؟">
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
                        <td colspan="6" style="text-align:center; padding:40px; color:var(--text-muted);">هیچ ڕووداوێک نەدۆزرایەوە.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $events->links('pagination::bootstrap-4') }}
</div>

@endsection
