@extends('admin.layouts.app')

@section('title', 'داواکارییەکانی خوێندنگا')

@section('content')

<div class="page-header">
    <div>
        <h1>داواکارییەکانی خوێندنگا</h1>
        <div class="breadcrumb">ئەو داواکاریانەی لەلایەن بەکارهێنەرانەوە نێردراون بۆ دروستکردنی خوێندنگا</div>
    </div>
</div>

<div class="card">
    <form method="GET" action="{{ route('admin.inst-requests.index') }}" class="filter-bar">
        <div class="form-group">
            <label class="form-label">دۆخ</label>
            <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="">هەمووی</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>چاوەڕوان</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>پەسەندکراو</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>ڕەتکراوە</option>
            </select>
        </div>

        @if(request()->filled('status'))
            <div class="form-group" style="flex: 0;">
                <a href="{{ route('admin.inst-requests.index') }}" class="btn btn-ghost" title="پاککردنەوە" style="height: 42.5px;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            </div>
        @endif
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ناوی خوێندنگا</th>
                    <th>تەلەفۆن</th>
                    <th>بەکارهێنەر</th>
                    <th>بەروار</th>
                    <th>دۆخ</th>
                    <th style="text-align:left;">کردارەکان</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                    <tr>
                        <td class="fw-bold">{{ $req->name }}</td>
                        <td dir="ltr" style="text-align:right;">{{ $req->phone }}</td>
                        <td>
                            @if($req->user)
                                <span class="badge badge-gray">{{ $req->user->name }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="td-muted">{{ $req->created_at->format('Y/m/d H:i') }}</td>
                        <td>
                            @if($req->status == 'pending')
                                <span class="badge badge-warning">چاوەڕوان</span>
                            @elseif($req->status == 'approved')
                                <span class="badge badge-success">پەسەندکراو</span>
                            @else
                                <span class="badge badge-danger">ڕەتکراوە</span>
                            @endif
                        </td>
                        <td style="text-align:left;">
                            <div class="d-flex gap-2" style="justify-content:flex-end;">
                                @if($req->status == 'pending')
                                    <form action="{{ route('admin.inst-requests.approve', $req) }}" method="POST" class="confirm-action" data-confirm="دڵنیایت؟ بەم کردارە خوێندنگایەکی نوێ دروست دەبێت بۆ ئەم بەکارهێنەرە.">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-xs" title="پەسەندکردن">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.inst-requests.reject', $req) }}" method="POST" class="confirm-action" data-confirm="دڵنیایت لە ڕەتکردنەوەی ئەم داواکارییە؟">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-xs" title="ڕەتکردنەوە">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.inst-requests.destroy', $req) }}" method="POST" class="confirm-action" data-confirm="دڵنیایت لە سڕینەوەی تەواوەتی ئەم داواکارییە؟">
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
                        <td colspan="6" style="text-align:center; padding:40px; color:var(--text-muted);">هیچ داواکارییەک نەدۆزرایەوە.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $requests->links('pagination::bootstrap-4') }}
</div>

@endsection
