@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row align-items-center mb-3">
            <div class="col-md-6 d-flex flex-wrap align-items-center gap-2">
                <h1 class="h3 mb-0">Dashboard</h1>
                @if (request('uptime_poll', 0))
                    <a href="{{ route('home', ['uptime_poll' => 0] + Request::except(['uptime_poll'])) }}"
                        class="btn btn-danger    -sm">STOP Monitoring</a>
                @else
                    <a href="{{ route('home', ['uptime_poll' => 1] + Request::except(['uptime_poll'])) }}"
                        class="btn btn-info btn-sm">START Monitoring</a>
                @endif
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-md-end">
                    {{ Form::open(['method' => 'get', 'class' => 'row row-cols-lg-auto g-2 align-items-center']) }}
                    <div class="col">
                        {!! Form::text('q', request('q'), [
                            'placeholder' => __('app.search'),
                            'class' => 'form-control',
                            'style' => 'width:160px',
                        ]) !!}
                    </div>
                    <div class="col">
                        {!! Form::select('vendor_id', $availableVendors, request('vendor_id'), [
                            'placeholder' => __('vendor.all'),
                            'class' => 'form-select',
                        ]) !!}
                    </div>
                    <div class="col d-flex gap-2">
                        {{ Form::hidden('uptime_poll', request('uptime_poll', 0)) }}
                        {{ Form::submit(__('app.search'), ['class' => 'btn btn-primary']) }}
                        {{ link_to_route('home', __('app.reset'), Request::only(['uptime_poll']), ['class' => 'btn btn-secondary']) }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

        <div class="row g-2 mb-4">
            @foreach ($sites as $site)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <a href="{{ route('sites.show', [$site]) }}" class="text-decoration-none d-block h-100">
                        <div class="card shadow-sm h-100 card-hover">
                            <div class="card-body py-2 px-3">
                                <div class="row">
                                    <div class="col-6 px-1">
                                        <div class="fw-semibold text-body">{{ $site->name }}</div>
                                        <span class="badge bg-secondary">{{ $site->vendor->name }}</span>
                                    </div>
                                    <div class="col-6 px-1 text-end">
                                        <div class="small"
                                            title="{{ __('site.check_interval') }}: {{ __('time.every') }} {{ $site->check_interval }} {{ trans_choice('time.minutes', $site->check_interval) }}">
                                            {{ $site->check_interval }}
                                            {{ trans_choice('time.minutes', $site->check_interval) }}
                                        </div>
                                        @livewire('uptime-badge', [
                                            'site' => $site,
                                            'uptimePoll' => request('uptime_poll', 0),
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .log_indicator {
            padding: 4px 1px;
            cursor: pointer;
            margin-left: -0.4px;
        }

        .card-hover {
            transition: transform .15s ease-in-out;
        }

        .card-hover:hover {
            transform: scale(1.02);
        }
    </style>
@endpush
