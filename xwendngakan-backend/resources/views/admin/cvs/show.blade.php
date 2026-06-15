@extends('admin.layouts.app')

@section('title', 'زانیاری سی ڤی: ' . $cv->name)

@section('content')

<div class="page-header">
    <div>
        <h1>زانیاری سی ڤی</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.cvs.index') }}">سی ڤییەکان</a> / {{ $cv->name }}
        </div>
    </div>
    
    <div class="d-flex gap-2">
        <form action="{{ route('admin.cvs.toggle', $cv) }}" method="POST">
            @csrf
            <button type="submit" class="btn {{ $cv->is_reviewed ? 'btn-ghost' : 'btn-success' }}">
                @if($cv->is_reviewed)
                    لابردنی پشکنین
                @else
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    دەستنیشانکردن وەک پشکنینکراو
                @endif
            </button>
        </form>
        <form action="{{ route('admin.cvs.destroy', $cv) }}" method="POST" class="confirm-action" data-confirm="دڵنیایت لە سڕینەوەی ئەم CVیە؟">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                سڕینەوە
            </button>
        </form>
    </div>
</div>

<div class="card" style="max-width: 900px; padding: 32px;">
    
    <div class="d-flex align-center gap-3 mb-4" style="border-bottom: 1px solid var(--border); padding-bottom: 24px;">
        @if($cv->photo)
            <img src="{{ $cv->photo }}" alt="Photo" style="width:100px; height:100px; border-radius:16px; object-fit:cover; border:2px solid var(--border);">
        @else
            <div class="avatar-placeholder" style="width:100px; height:100px; border-radius:16px; font-size:32px;">
                {{ mb_substr($cv->name, 0, 1) }}
            </div>
        @endif
        
        <div>
            <h2 class="fw-bold" style="font-size:24px; margin-bottom:4px;">{{ $cv->name }}</h2>
            <div class="text-muted" style="font-size:15px;">{{ $cv->field }}</div>
            <div class="mt-2">
                @if($cv->is_reviewed)
                    <span class="badge badge-success">پشکنینکراو</span>
                @else
                    <span class="badge badge-warning">نەپشکنینکراو</span>
                @endif
                <span class="badge badge-gray mr-2" dir="ltr" style="margin-right: 8px;">{{ $cv->created_at->format('Y/m/d H:i') }}</span>
            </div>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom: 32px;">
        <div>
            <div class="text-faint text-xs fw-bold mb-1">ئاستی خوێندن</div>
            <div class="fw-semibold text-main">{{ $cv->education_level }}</div>
        </div>
        <div>
            <div class="text-faint text-xs fw-bold mb-1">ساڵانی ئەزموون</div>
            <div class="fw-semibold text-main">{{ $cv->experience_years ?? 0 }} ساڵ</div>
        </div>
        <div>
            <div class="text-faint text-xs fw-bold mb-1">تەلەفۆن</div>
            <div class="fw-semibold text-main" dir="ltr" style="text-align:right;">{{ $cv->phone }}</div>
        </div>
        <div>
            <div class="text-faint text-xs fw-bold mb-1">شار</div>
            <div class="fw-semibold text-main">{{ $cv->city }}</div>
        </div>
        <div style="grid-column: span 2;">
            <div class="text-faint text-xs fw-bold mb-1">سی ڤی / فایلی پەیوەندیدار</div>
            @if($cv->pdf_file)
                <a href="{{ $cv->pdf_file }}" target="_blank" class="btn btn-ghost mt-1">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    داگرتنی فایل
                </a>
            @else
                <div class="text-muted">فایلی هاوپێچ نەکراوە</div>
            @endif
        </div>
    </div>

    <div>
        <div class="text-faint text-xs fw-bold mb-2">دەق / زانیاری زیاتر</div>
        <div class="form-control" style="background:var(--bg-base); border:none; padding:16px; min-height:120px; font-size:14px;">
            {!! nl2br(e($cv->bio ?? 'هیچ زانیارییەکی زیاتر نەنووسراوە.')) !!}
        </div>
    </div>

</div>

@endsection
