@extends('layouts.app')

@section('title', __('customer_site.create'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('customer_site.create') }}</div>
            {{ Form::open(['route' => 'customer_sites.store']) }}
            <div class="card-body">
                {!! FormField::text('name', ['required' => true, 'label' => __('customer_site.name')]) !!}
                {!! FormField::text('url', ['label' => __('customer_site.url')]) !!}
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
