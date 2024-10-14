@extends('layouts.app')

@section('title', __('customer_site.create'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('customer_site.create') }}</div>
            {{ Form::open(['route' => 'customer_sites.store']) }}
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        {!! FormField::text('name', ['required' => true, 'label' => __('customer_site.name'), 'placeholder' => 'Example Web']) !!}
                    </div>
                    <div class="col-md-8">
                        {!! FormField::text('webhook_url', ['required' => false, 'label' => __('customer_site.webhook'), 'placeholder' => 'Example Web']) !!}
                    </div>
                    <div class="col-md-4">
                        {!! FormField::select('vendor_id', $availableVendors, ['label' => __('vendor.vendor')]) !!}
                    </div>
                </div>
                {!! FormField::text('url', ['label' => __('customer_site.url'), 'placeholder' => 'https://example.net']) !!}
            </div>
            <div class="card-footer">
                {{ Form::submit(__('app.create'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('customer_sites.index', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
