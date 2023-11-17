@extends('layouts.app')

@section('content')

<div class="page-header mt-0 mb-4">
    <h1 class="page-title">Uptime Monitor</h1>
</div>
<div class="text-end me-3">last 60 minutes</div>

<div class="row mb-4">
    @foreach ($customerSites as $customerSite)
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    @can('view', $customerSite)
                        {{ link_to_route(
                            'customer_sites.show',
                            __('app.show'),
                            [$customerSite],
                            ['id' => 'show-customer_site-' . $customerSite->id, 'class' => 'float-end']
                        ) }}
                    @endcan
                    {{ $customerSite->name }}
                </div>
                <div class="card-body">
                    {{-- <ul class="pl-1">
                        <li>URL: {{ $customerSite->url }}</li>
                    </ul> --}}
                    @php
                        $monitoringLogs = $customerSite->monitoringLogs()
                            ->whereBetween('created_at', [Carbon::now()->subHours(1), Carbon::now()])
                            ->get(['response_time', 'created_at']);;
                    @endphp
                    <div class="text-center">
                        @foreach ($monitoringLogs as $monitoringLog)
                            @php
                                $bgColor = 'success';
                                if ($monitoringLog->response_time > 5000) {
                                    $bgColor = 'warning';
                                }
                                if ($monitoringLog->response_time > 10000) {
                                    $bgColor = 'danger';
                                }
                            @endphp
                            <span class="badge bg-{{ $bgColor }} log_indicator" title="{{ $monitoringLog->created_at }} ({{ number_format($monitoringLog->response_time) }} ms)">&nbsp;</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@push('styles')
<style>
    .log_indicator {
        padding: 4px 1px;
        cursor: pointer;
    }
</style>
@endpush
