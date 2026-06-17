@extends('admin.layouts.app')

@section('title', 'ڕۆژژمێری ئەکادیمی')

@section('content')

<div class="page-header">
    <div>
        <h1>ڕۆژژمێری ئەکادیمی</h1>
        <div class="breadcrumb">بەڕێوەبردنی پشوو، تاقیکردنەوە و وادە گرنگەکانی خوێندن</div>
    </div>
    <a href="{{ route('admin.academic-calendar.create') }}" class="btn btn-primary">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        ڕووداوی نوێ
    </a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ڕووداو / سەردێڕ</th>
                    <th>جۆر / پۆلێن</th>
                    <th>بەرواری دەستپێکردن</th>
                    <th>ماوە (ڕۆژ)</th>
                    <th>ئایکۆن</th>
                    <th style="text-align:left;">کردارەکان</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $item)
                    <tr>
                        <td class="fw-bold">
                            {{ $item->title }}
                            @if($item->description)
                                <div style="font-size: 11px; color: var(--text-muted); font-weight: normal; margin-top: 4px;">{{ Str::limit($item->description, 70) }}</div>
                            @endif
                        </td>
                        <td>
                            @if($item->category == 'holiday')
                                <span class="badge badge-success">پشوو</span>
                            @elseif($item->category == 'exam')
                                <span class="badge badge-primary">تاقیکردنەوە یان قوتابخانە</span>
                            @elseif($item->category == 'deadline')
                                <span class="badge badge-warning">تۆمارکردن یان وادە</span>
                            @else
                                <span class="badge badge-muted">{{ $item->category }}</span>
                            @endif
                        </td>
                        <td class="td-muted">{{ $item->date->format('Y/m/d') }}</td>
                        <td>{{ $item->duration_days }} ڕۆژ</td>
                        <td class="td-muted"><span dir="ltr">{{ $item->icon }}</span></td>
                        <td style="text-align:left;">
                            <div class="d-flex gap-2" style="justify-content:flex-end;">
                                <a href="{{ route('admin.academic-calendar.edit', $item) }}" class="btn btn-ghost btn-xs" title="دەستکاری">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.academic-calendar.destroy', $item) }}" method="POST" class="confirm-action" data-confirm="دڵنیایت لە سڕینەوەی ئەم ڕووداوە لە ڕۆژژمێردا؟">
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
                        <td colspan="6" style="text-align:center; padding:40px; color:var(--text-muted);">هیچ ڕووداوێکی ئەکادیمی نەدۆزرایەوە.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $events->links('pagination::bootstrap-4') }}
</div>

@endsection
