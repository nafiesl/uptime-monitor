@extends('layouts.app')

@section('content')

{{-- <script src="https://cdn.tailwindcss.com"></script> --}}

<div class="row mb-3">
    <div class="col-md-12">
        <div class="float-end">
            {{ Form::open(['method' => 'get', 'route' => 'home', 'class' => 'row row-cols-lg-auto g-2 align-items-center']) }}
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

@php
    $vendorGroups = $vendors->map(fn ($vendor) => ['name' => $vendor->name, 'sites' => $sites->where('vendor_id', $vendor->id)])
        ->push(['name' => __('vendor.without_vendor'), 'sites' => $sites->whereNull('vendor_id')])
        ->filter(fn ($vendorGroup) => $vendorGroup['sites']->isNotEmpty());
@endphp

<main class="p-8 space-y-6">
    <!-- 3 Kolom Website Status -->
    <div class="row grid-cols-1 lg:grid-cols-3 gap-2">

        <!-- Card -->
        @forelse($vendorGroups as $vendorGroup)


        <div class="bg-white rounded shadow-sm p-4 col ">
            <div class="flex justify-between items-center mb-6">
            <h2 class="text-green-600 h4">{{ $vendorGroup['name'] }}</h2>
            </div>
            <div class="space-y-2">


            @foreach ($vendorGroup['sites'] as $site)

            <a href="{{ route('sites.show', [$site]) }}" class="d-flex justify-content-between border-b pb-1 text-decoration-none site">
                <span class="text-sm font-bold"> {{ $loop->iteration }}. {{ $site->name }}</span>            
                
                @livewire('uptime-badge', [
                                    'site' => $site,
                                    'uptimePoll' => request('uptime_poll', 0)
                                ])

            </a>
            

            @endforeach


            
            <!-- Repeat row as needed -->
            </div>
        </div>

        @empty
        <div class="col text-center text-muted">{{ __('site.not_found') }}</div>
        @endforelse

    
    </div>

 
  </main>

  @push('styles')
    <style>
        .log_indicator {
            padding: 4px 1px;
            cursor: pointer;
            margin-left: -0.4px;
        }
        .site:hover {
            transform: scale(1.02);
            background-color: #f0f0f0;
        }
    </style>
  @endpush

@endsection