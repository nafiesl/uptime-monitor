@extends('layouts.app')

@section('content')

<div class="page-header mt-0 mb-4">
    <div class="float-end">
        {{ Form::open(['method' => 'get', 'class' => 'row row-cols-lg-auto g-2 align-items-center']) }}
        <div class="col-12">
            {!! FormField::text('q', ['label' => false, 'value' => request('q'), 'placeholder' => __('app.search'), 'style' => 'width:160px;margin-bottom:-16px']) !!}
        </div>
        <div class="col-12">
            {!! FormField::select('vendor_id', $availableVendors, ['label' => false, 'value' => request('vendor_id'), 'placeholder' => __('vendor.all'), 'style' => 'margin-bottom:-16px']) !!}
        </div>
        <div class="col-12">
            {{ Form::submit(__('app.search'), ['class' => 'btn btn-secondary']) }}
            {{ link_to_route('home', __('app.reset'), [], ['class' => 'btn btn-link']) }}
        </div>
        {{ Form::close() }}
    </div>
    <h1 class="page-title">
        Dashboard
        @php
            $uptimePoll = request('uptime_poll', 0);
        @endphp
        @if ($uptimePoll)
            <a href="{{ route('home', ['uptime_poll' => 0]) }}" class="btn btn-danger">
                STOP Monitoring
            </a>
        @else
            <a href="{{ route('home', ['uptime_poll' => 1]) }}" class="btn btn-info">
                START Monitoring
            </a>
        @endif
    </h1>
</div>

<div class="row mb-4">
    @foreach ($customerSites as $customerSite)
        <a href="{{ route('customer_sites.show', [$customerSite]) }}" class="col-md-4 mb-4 text-decoration-none">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-around">
                        <div class="col">{{ $customerSite->name }}</div>
                        <div class="col text-end">
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
    }
</style>
@endpush
