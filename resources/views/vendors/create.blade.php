@extends('layouts.app')

@section('title', __('vendor.create'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('vendor.create') }}</div>
            {{ Form::open(['route' => 'vendors.store']) }}
            <div class="card-body">
                {!! FormField::text('name', ['required' => true, 'label' => __('vendor.name')]) !!}
                {!! FormField::textarea('description', ['label' => __('vendor.description')]) !!}
            </div>
            <div class="card-footer">
                {{ Form::submit(__('app.create'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('vendors.index', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
