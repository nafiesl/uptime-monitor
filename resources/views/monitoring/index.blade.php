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
                            @livewire('uptime-badge', ['customerSite' => $customerSite])
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
