@extends('admin.layouts.app')

@section('title', 'داشبۆرد')

@section('content')

<div class="page-header">
    <div>
        <h1>بەخێربێیتەوە، {{ auth()->guard('web')->user()->name }} 👋</h1>
        <div class="breadcrumb">پوختەی ئامارەکانی سیستەم</div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card" style="--stat-color: var(--primary);">
        <div class="stat-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
        <div class="stat-label">خوێندنگاکان</div>
        <div class="stat-value">{{ $stats['total_institutions'] }}</div>
        <div class="stat-desc text-success">{{ $stats['approved_institutions'] }} پەسەندکراو</div>
    </div>
    
    <div class="stat-card" style="--stat-color: var(--info);">
        <div class="stat-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
        <div class="stat-label">بەکارهێنەران</div>
        <div class="stat-value">{{ $stats['total_users'] }}</div>
        <div class="stat-desc">تەواوی بەکارهێنەران</div>
    </div>

    <div class="stat-card" style="--stat-color: var(--success);">
        <div class="stat-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
        <div class="stat-label">مامۆستاکان</div>
        <div class="stat-value">{{ $stats['total_teachers'] }}</div>
        <div class="stat-desc text-warning">{{ $stats['pending_teachers'] }} چاوەڕێی پەسەندکردن</div>
    </div>

    <div class="stat-card" style="--stat-color: var(--warning);">
        <div class="stat-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
        <div class="stat-label">CVکان</div>
        <div class="stat-value">{{ $stats['total_cvs'] }}</div>
        <div class="stat-desc text-warning">{{ $stats['pending_cvs'] }} نەپشکنینکراو</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <!-- Recent Pending Institutions -->
    <div class="card">
        <h3 class="fw-bold mb-4 d-flex align-center gap-2">
            <svg style="width:20px;height:20px;color:var(--warning)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            داواکارییە تازەکان (چاوەڕوانی پەسەندکردن)
        </h3>
        
        @if($pending->count() > 0)
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ناوی دامەزراوە</th>
                            <th>جۆر</th>
                            <th>شار</th>
                            <th>بەروار</th>
                            <th>کردارەکان</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pending as $inst)
                            <tr>
                                <td class="fw-bold">{{ $inst->nku }}</td>
                                <td>
                                    @php
                                        $typeLabels = [
                                            'gov' => ['حکومی', 'badge-primary'],
                                            'priv' => ['ئەهلی', 'badge-success'],
                                            'school' => ['قوتابخانە', 'badge-warning'],
                                            'kg' => ['باخچە', 'badge-info']
                                        ];
                                        $t = $typeLabels[$inst->type] ?? [$inst->type, 'badge-gray'];
                                    @endphp
                                    <span class="badge {{ $t[1] }}">{{ $t[0] }}</span>
                                </td>
                                <td>{{ $inst->city }}</td>
                                <td class="td-muted">{{ $inst->created_at->format('Y/m/d H:i') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('admin.institutions.toggle', $inst) }}" method="POST" class="confirm-action" data-confirm="دڵنیایت لە پەسەندکردنی ئەم خوێندنگایە؟">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-xs" title="پەسەندکردن">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.institutions.destroy', $inst) }}" method="POST" class="confirm-action" data-confirm="دڵنیایت لە سڕینەوەی ئەم داواکارییە؟">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs" title="ڕەتکردنەوە">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align:center; padding:40px; color:var(--text-muted);">
                <svg style="width:48px;height:48px;margin:0 auto 12px;opacity:0.5;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p>هیچ داواکارییەکی تازە نییە، هەموو خوێندنگاکان پەسەندکراون.</p>
            </div>
        @endif
    </div>

    <!-- Chart -->
    <div class="card">
        <h3 class="fw-bold mb-4 d-flex align-center gap-2">
            <svg style="width:20px;height:20px;color:var(--info)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
            خوێندنگاکان بەپێی جۆر
        </h3>
        
        <div class="chart-wrap" style="height: 280px; position: relative;">
            <canvas id="typeChart"></canvas>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('typeChart').getContext('2d');
    
    const labels = {!! json_encode($chartLabels) !!};
    const data = {!! json_encode($chartData) !!};
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    '#3B82F6', // gov
                    '#10B981', // priv
                    '#8B5CF6', // inst5
                    '#6366F1', // inst2
                    '#F59E0B', // school
                    '#06B6D4', // kg
                    '#EC4899', // dc
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#8888aa',
                        padding: 16,
                        usePointStyle: true,
                        font: { family: 'Vazirmatn' }
                    }
                }
            }
        }
    });
});
</script>

@endsection
