@extends('admin.layouts.app')

@section('title', 'مامۆستاکان')

@section('content')

<div class="page-header">
    <div>
        <h1>مامۆستاکان</h1>
        <div class="breadcrumb">لیست و پەسەندکردنی پرۆفایلی مامۆستاکان</div>
    </div>
</div>

<div class="card">
    <form method="GET" action="{{ route('admin.teachers.index') }}" class="filter-bar">
        <div class="form-group">
            <label class="form-label">گەڕان</label>
            <div class="search-input-wrap">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" class="form-control" placeholder="ناو، بابەت، تەلەفۆن..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">جۆر</label>
            <select name="type" class="form-control" onchange="this.form.submit()">
                <option value="">هەمووی</option>
                <option value="university" {{ request('type') === 'university' ? 'selected' : '' }}>زانکۆ / پەیمانگا</option>
                <option value="school" {{ request('type') === 'school' ? 'selected' : '' }}>قوتابخانە</option>
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

        @if(request()->anyFilled(['search', 'type', 'approved']))
            <div class="form-group" style="flex: 0;">
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-ghost" title="پاککردنەوە" style="height: 42.5px;">
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
                    <th>پسپۆڕی</th>
                    <th>جۆر</th>
                    <th>شار</th>
                    <th>دۆخ</th>
                    <th style="text-align:left;">کردارەکان</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $teacher)
                    <tr>
                        <td>
                            @if($teacher->photo)
                                <img src="{{ $teacher->photo }}" class="avatar" alt="Photo">
                            @else
                                <div class="avatar-placeholder">{{ mb_substr($teacher->name, 0, 1) }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">{{ $teacher->name }}</div>
                            <div class="td-muted" dir="ltr" style="text-align:right;">{{ $teacher->phone }}</div>
                        </td>
                        <td>{{ $teacher->subject }}</td>
                        <td>
                            @if($teacher->type == 'university')
                                <span class="badge badge-primary">زانکۆ/پەیمانگا</span>
                            @else
                                <span class="badge badge-info">قوتابخانە</span>
                            @endif
                        </td>
                        <td>{{ $teacher->city }}</td>
                        <td>
                            @if($teacher->is_approved)
                                <span class="badge badge-success">پەسەندکراو</span>
                            @else
                                <span class="badge badge-warning">چاوەڕوان</span>
                            @endif
                        </td>
                        <td style="text-align:left;">
                            <div class="d-flex gap-2" style="justify-content:flex-end;">
                                <form action="{{ route('admin.teachers.toggle', $teacher) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $teacher->is_approved ? 'btn-ghost' : 'btn-success' }} btn-xs" title="{{ $teacher->is_approved ? 'لابردنی پەسەند' : 'پەسەندکردن' }}">
                                        @if($teacher->is_approved)
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                    </button>
                                </form>
                                <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" class="confirm-action" data-confirm="دڵنیایت لە سڕینەوەی ئەم مامۆستایە؟">
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
                        <td colspan="7" style="text-align:center; padding:40px; color:var(--text-muted);">هیچ مامۆستایەک نەدۆزرایەوە.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $teachers->links('pagination::bootstrap-4') }}
</div>

@endsection
