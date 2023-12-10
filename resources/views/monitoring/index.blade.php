@extends('layouts.app')

@section('content')

<div class="page-header mt-0 mb-4">
    <h1 class="page-title">Dashboard</h1>
</div>

<div class="row mb-4">
    @foreach ($customerSites as $customerSite)
        <a href="{{ route('customer_sites.show', [$customerSite]) }}" class="col-md-4 mb-4 text-decoration-none">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-around">
                        <div class="col">{{ $customerSite->name }}</div>
                        <div class="col text-end">
                            @php
                                $monitoringLogs = $customerSite->monitoringLogs()
                                    ->latest('id')
                                    ->take(15)
                                    ->get(['response_time', 'created_at'])
                                    ->sort();
                            @endphp
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
        </a>
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
