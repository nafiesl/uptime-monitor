@extends('layouts.app')

@section('title', __('site.create'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('site.create') }}</div>
            {{ Form::open(['route' => 'sites.store']) }}
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        {!! FormField::text('name', ['required' => true, 'label' => __('site.name'), 'placeholder' => 'Example Web']) !!}
                    </div>
                    <div class="col-md-4">
                        {!! FormField::select('vendor_id', $availableVendors, ['label' => __('vendor.vendor')]) !!}
                    </div>
                </div>
                {!! FormField::text('url', ['label' => __('site.url'), 'placeholder' => 'https://example.net']) !!}
            </div>
            <div class="card-footer">
                {{ Form::submit(__('app.create'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('sites.index', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
