@extends('admin.layouts.app')

@section('title', 'بانەرەکان')

@section('content')

<div class="page-header">
    <div>
        <h1>بانەرەکان</h1>
        <div class="breadcrumb">بەڕێوەبردنی بانەرەکانی شاشەی سەرەکی ئەپڵیکەیشن</div>
    </div>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        زیادکردنی بانەر
    </a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:70px">وێنە</th>
                    <th>تاگ</th>
                    <th>ڕیزبەندی</th>
                    <th>دۆخ</th>
                    <th style="text-align:left;">کردارەکان</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                    <tr>
                        <td>
                            @if($banner->image)
                                <img src="{{ $banner->image }}" class="img-preview" style="height:50px; width:50px; border-radius:8px; margin:0;" alt="Image">
                            @else
                                <div class="avatar-placeholder" style="border-radius:8px; width:50px; height:50px;">
                                    <svg style="width:20px;height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($banner->tag)
                                <span class="badge badge-primary mt-1" style="font-size:10px;">{{ $banner->tag }}</span>
                            @else
                                <span class="td-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $banner->sort_order }}</td>
                        <td>
                            @if($banner->is_active)
                                <span class="badge badge-success">چالاک</span>
                            @else
                                <span class="badge badge-danger">ناچالاک</span>
                            @endif
                        </td>
                        <td style="text-align:left;">
                            <div class="d-flex gap-2" style="justify-content:flex-end;">
                                <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-ghost btn-xs" title="دەستکاری">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="confirm-action" data-confirm="دڵنیایت لە سڕینەوەی ئەم بانەرە؟">
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
                        <td colspan="6" style="text-align:center; padding:40px; color:var(--text-muted);">هیچ بانەرێک نەدۆزرایەوە.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $banners->links('pagination::bootstrap-4') }}
</div>

@endsection
