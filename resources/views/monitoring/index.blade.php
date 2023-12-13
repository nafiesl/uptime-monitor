@extends('layouts.app')

@section('content')

<div class="page-header mt-0 mb-4">
    <div class="float-end">
        @php
            $uptimePoll = request('uptime_poll', 0);
        @endphp
        <a href="{{ route('home', ['uptime_poll' => $uptimePoll ? 0 : 1]) }}" class="text-decoration-none fs-3 text-dark">
            @if ($uptimePoll)
                Stop &#9208;&#65039;
            @else
                Start &#9654;&#65039;
            @endif
        </a>
    </div>
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
