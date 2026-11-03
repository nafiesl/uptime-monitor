@extends('layouts.app')

@php
    $vendorGroups = $vendors->map(fn ($vendor) => ['name' => $vendor->name, 'sites' => $sites->where('vendor_id', $vendor->id)])
        ->push(['name' => __('vendor.without_vendor'), 'sites' => $sites->whereNull('vendor_id')])
        ->filter(fn ($vendorGroup) => $vendorGroup['sites']->isNotEmpty())
        ->values();

    $siteStatuses = $sites->mapWithKeys(fn ($site) => [
        $site->id => $site->latestMonitoringLog ? $site->getStatusColor($site->latestMonitoringLog) : 'secondary',
    ]);
    $statusCounts = $siteStatuses->countBy();

    $summaryTiles = [
        ['label' => 'Total Site', 'count' => $sites->count(), 'class' => 'tile-total', 'icon' => '◎'],
        ['label' => 'Up', 'count' => $statusCounts->get('success', 0), 'class' => 'tile-up', 'icon' => '▲'],
        ['label' => 'Warning', 'count' => $statusCounts->get('warning', 0), 'class' => 'tile-warning', 'icon' => '!'],
        ['label' => 'Down', 'count' => $statusCounts->get('danger', 0), 'class' => 'tile-down', 'icon' => '▼'],
    ];
    $accentColors = ['#3b82f6', '#8b5cf6', '#06b6d4', '#f59e0b', '#ec4899', '#10b981', '#f97316'];
    $isPolling = (bool) request('uptime_poll', 0);
@endphp

@section('content')
<div class="dashboard2">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="dashboard-title mb-1">Dashboard</h1>
            <div class="text-secondary small">
                @if ($isPolling)
                    <span class="live-dot"></span> Monitoring aktif, status diperbarui otomatis
                @else
                    Status uptime website yang Anda pantau
                @endif
            </div>
        </div>
        @if ($isPolling)
            <a href="{{ route('home', ['uptime_poll' => 0] + Request::except(['uptime_poll'])) }}" class="btn btn-pill btn-stop">■ STOP Monitoring</a>
        @else
            <a href="{{ route('home', ['uptime_poll' => 1] + Request::except(['uptime_poll'])) }}" class="btn btn-pill btn-start">▶ START Monitoring</a>
        @endif
    </div>

    <div class="row g-3 mb-4">
        @foreach ($summaryTiles as $summaryTile)
            <div class="col-6 col-lg-3">
                <div class="summary-tile {{ $summaryTile['class'] }}">
                    <div class="summary-icon">{{ $summaryTile['icon'] }}</div>
                    <div>
                        <div class="summary-count">{{ $summaryTile['count'] }}</div>
                        <div class="summary-label">{{ $summaryTile['label'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="toolbar-card mb-4">
        {{ Form::open(['method' => 'get', 'route' => 'home', 'class' => 'row g-2 align-items-center']) }}
            <div class="col-md-5">
                {!! Form::text('q', request('q'), ['placeholder' => __('site.search').'...', 'class' => 'form-control form-control-pill']) !!}
            </div>
            <div class="col-md-4">
                {!! Form::select('vendor_id', $availableVendors, request('vendor_id'), ['placeholder' => __('vendor.all'), 'class' => 'form-select form-control-pill']) !!}
            </div>
            <div class="col-md-3 d-flex gap-2">
                {{ Form::hidden('uptime_poll', request('uptime_poll', 0)) }}
                {{ Form::submit(__('app.search'), ['class' => 'btn btn-pill btn-search flex-fill']) }}
                {{ link_to_route('home', __('app.reset'), Request::only(['uptime_poll']), ['class' => 'btn btn-pill btn-reset']) }}
            </div>
        {{ Form::close() }}
    </div>

    <div class="row row-cols-1 row-cols-lg-2 row-cols-xxl-3 g-4">
        @forelse ($vendorGroups as $vendorGroup)
            @php($accentColor = $accentColors[$loop->index % count($accentColors)])
            <div class="col">
                <div class="vendor-card h-100" style="--accent: {{ $accentColor }}">
                    <div class="vendor-card-header">
                        <div class="d-flex align-items-center gap-2">
                            <span class="vendor-avatar">{{ strtoupper(mb_substr($vendorGroup['name'], 0, 1)) }}</span>
                            <h2 class="vendor-name">{{ $vendorGroup['name'] }}</h2>
                        </div>
                        <span class="vendor-count">{{ $vendorGroup['sites']->count() }} site</span>
                    </div>
                    <div class="vendor-card-body">
                        @foreach ($vendorGroup['sites'] as $site)
                            <a href="{{ route('sites.show', [$site]) }}" class="site-row">
                                <div class="site-info">
                                    <span class="status-dot status-{{ $siteStatuses[$site->id] }}"></span>
                                    <div class="text-truncate">
                                        <div class="site-name text-truncate">{{ $site->name }}</div>
                                        <div class="site-url text-truncate">{{ parse_url($site->url, PHP_URL_HOST) ?: $site->url }}</div>
                                    </div>
                                </div>
                                <div class="site-badges">
                                    @livewire('uptime-badge', [
                                        'site' => $site,
                                        'uptimePoll' => request('uptime_poll', 0)
                                    ], key('uptime-badge-'.$site->id))
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <div class="empty-icon">🔍</div>
                    <div class="fw-bold">{{ __('site.not_found') }}</div>
                    <div class="text-secondary small">Coba kata kunci atau vendor lain.</div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        background: linear-gradient(180deg, #eef4ff 0%, #f8fbff 40%, #ffffff 100%);
        background-attachment: fixed;
    }
    .dashboard2 { font-family: 'Nunito', sans-serif; }
    .dashboard-title { font-weight: 800; color: #1e293b; font-size: 1.9rem; }
    .live-dot {
        display: inline-block; width: 8px; height: 8px; border-radius: 50%;
        background: #22c55e; margin-right: 4px; animation: pulse 1.6s infinite;
    }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, .6); }
        70% { box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
        100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }

    .btn-pill { border-radius: 999px; font-weight: 700; padding: .5rem 1.2rem; border: 0; }
    .btn-start { background: linear-gradient(135deg, #22c55e, #10b981); color: #fff; box-shadow: 0 6px 16px rgba(16, 185, 129, .3); }
    .btn-stop { background: linear-gradient(135deg, #f87171, #ef4444); color: #fff; box-shadow: 0 6px 16px rgba(239, 68, 68, .3); }
    .btn-start:hover, .btn-stop:hover, .btn-search:hover { color: #fff; filter: brightness(1.05); transform: translateY(-1px); }
    .btn-search { background: linear-gradient(135deg, #3b82f6, #6366f1); color: #fff; }
    .btn-reset { background: #f1f5f9; color: #475569; }
    .btn-reset:hover { background: #e2e8f0; color: #1e293b; }

    .summary-tile {
        display: flex; align-items: center; gap: 1rem; padding: 1.1rem 1.25rem;
        border-radius: 1.1rem; background: #fff; box-shadow: 0 4px 18px rgba(15, 23, 42, .06);
        border: 1px solid rgba(15, 23, 42, .04);
    }
    .summary-icon {
        width: 46px; height: 46px; border-radius: .9rem; display: flex; align-items: center;
        justify-content: center; font-size: 1.2rem; font-weight: 800; flex-shrink: 0;
    }
    .summary-count { font-size: 1.6rem; font-weight: 800; line-height: 1; color: #1e293b; }
    .summary-label { font-size: .85rem; color: #64748b; margin-top: .2rem; }
    .tile-total .summary-icon { background: #dbeafe; color: #2563eb; }
    .tile-up .summary-icon { background: #dcfce7; color: #16a34a; }
    .tile-warning .summary-icon { background: #fef3c7; color: #d97706; }
    .tile-down .summary-icon { background: #fee2e2; color: #dc2626; }

    .toolbar-card {
        background: #fff; border-radius: 1.1rem; padding: .9rem;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .06);
    }
    .form-control-pill { border-radius: 999px; border-color: #e2e8f0; background-color: #f8fafc; padding-left: 1rem; }
    .form-control-pill:focus { background-color: #fff; border-color: #93c5fd; box-shadow: 0 0 0 .2rem rgba(59, 130, 246, .15); }

    .vendor-card {
        background: #fff; border-radius: 1.25rem; overflow: hidden;
        box-shadow: 0 6px 22px rgba(15, 23, 42, .07); border-top: 5px solid var(--accent);
        transition: box-shadow .2s, transform .2s;
    }
    .vendor-card:hover { box-shadow: 0 10px 30px rgba(15, 23, 42, .1); transform: translateY(-2px); }
    .vendor-card-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 1rem 1.25rem .75rem;
    }
    .vendor-avatar {
        width: 34px; height: 34px; border-radius: .7rem; display: inline-flex; align-items: center;
        justify-content: center; font-weight: 800; color: #fff; background: var(--accent);
    }
    .vendor-name { font-size: 1.1rem; font-weight: 800; color: #1e293b; margin: 0; }
    .vendor-count {
        font-size: .75rem; font-weight: 700; color: var(--accent); padding: .25rem .7rem;
        border-radius: 999px; background: color-mix(in srgb, var(--accent) 12%, #fff);
    }
    .vendor-card-body { padding: 0 .75rem .9rem; }

    .site-row {
        display: flex; justify-content: space-between; align-items: center; gap: .75rem;
        padding: .55rem .6rem; border-radius: .8rem; text-decoration: none; color: inherit;
        transition: background-color .15s;
    }
    .site-row + .site-row { border-top: 1px dashed #eef2f7; }
    .site-row:hover { background: color-mix(in srgb, var(--accent) 7%, #fff); }
    .site-info { display: flex; align-items: center; gap: .6rem; min-width: 0; }
    .site-name { font-weight: 700; color: #334155; font-size: .92rem; }
    .site-url { font-size: .75rem; color: #94a3b8; }
    .site-badges { flex-shrink: 0; }

    .status-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
    .status-success { background: #22c55e; box-shadow: 0 0 0 3px #dcfce7; }
    .status-warning { background: #f59e0b; box-shadow: 0 0 0 3px #fef3c7; }
    .status-danger { background: #ef4444; box-shadow: 0 0 0 3px #fee2e2; }
    .status-secondary { background: #cbd5e1; box-shadow: 0 0 0 3px #f1f5f9; }

    .log_indicator {
        display: inline-block; width: 6px; height: 22px; padding: 0; margin-left: 2px;
        border-radius: 3px; font-size: 0; vertical-align: middle; cursor: pointer;
        transition: transform .15s;
    }
    .log_indicator:hover { transform: scaleY(1.2); }
    .log_indicator.bg-success { background-color: #4ade80 !important; }
    .log_indicator.bg-warning { background-color: #fbbf24 !important; }
    .log_indicator.bg-danger { background-color: #f87171 !important; }

    .empty-state {
        background: #fff; border-radius: 1.25rem; padding: 3rem 1rem; text-align: center;
        box-shadow: 0 6px 22px rgba(15, 23, 42, .07);
    }
    .empty-icon { font-size: 2rem; margin-bottom: .5rem; }
</style>
@endpush
