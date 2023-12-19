@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-6">
        <h1>
            Dashboard
            @if (request('uptime_poll', 0))
                <a href="{{ route('home', ['uptime_poll' => 0] + Request::except(['uptime_poll'])) }}" class="btn btn-danger">STOP Monitoring</a>
            @else
                <a href="{{ route('home', ['uptime_poll' => 1] + Request::except(['uptime_poll'])) }}" class="btn btn-info">START Monitoring</a>
            @endif
        </h1>
    </div>
    <div class="col-md-6">
        <div class="float-end">
            {{ Form::open(['method' => 'get', 'class' => 'row row-cols-lg-auto g-2 align-items-center']) }}
            <div class="col-6">
                {!! Form::text('q', request('q'), ['placeholder' => __('app.search'), 'style' => 'width:160px']) !!}
            </div>
            <div class="col-6">
                {!! Form::select('vendor_id', $availableVendors, request('vendor_id'), ['placeholder' => __('vendor.all')]) !!}
            </div>
            <div class="col-12">
                {{ Form::hidden('uptime_poll', request('uptime_poll', 0)) }}
                {{ Form::submit(__('app.search')) }}
                {{ link_to_route('home', __('app.reset'), Request::only(['uptime_poll']), ['class' => 'btn btn-link']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<br>
<div class="row mb-4">
    @foreach ($customerSites as $customerSite)
        <a href="{{ route('customer_sites.show', [$customerSite]) }}" class="col-md-6 col-lg-4 col-xl-3 px-1 mb-2 text-decoration-none">
            <div class="card">
                <div class="card-body py-2 px-3">
                    <div class="row">
                        <div class="col-6 px-1">
                            {{ $customerSite->name }}<br>
                            <span class="badge bg-secondary">{{ $customerSite->vendor->name }}</span>
                        </div>
                        <div class="col-6 px-1 text-end">
                            <div class="small" title="{{ __('customer_sites.check_interval') }}: {{ __('time.every') }} {{ $customerSite->check_interval }} {{ trans_choice('time.minutes', $customerSite->check_interval) }}">
                                {{ $customerSite->check_interval }} {{ trans_choice('time.minutes', $customerSite->check_interval) }}
                            </div>
                            @livewire('uptime-badge', [
                                'customerSite' => $customerSite,
                                'uptimePoll' => request('uptime_poll', 0)
                            ])
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
        margin-left: -0.4px;
    }
    .card:hover {
        transform: scale(1.02);
    }
</style>
@endpush
