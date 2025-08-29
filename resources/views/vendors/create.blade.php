@extends('layouts.app')

@section('title', __('vendor.create'))

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">{{ __('vendor.create') }}</div>

                    {{ Form::open(['route' => 'vendors.store']) }}
                    <div class="card-body">
                        {!! FormField::text('name', [
                            'required' => true,
                            'label' => __('vendor.name'),
                            'placeholder' => 'Acme, Inc.',
                        ]) !!}

                        {!! FormField::textarea('description', [
                            'label' => __('vendor.description'),
                            'rows' => 4,
                        ]) !!}
                    </div>

                    <div class="card-footer d-flex justify-content-end gap-2">
                        {{ Form::submit(__('app.create'), ['class' => 'btn btn-success']) }}
                        {{ link_to_route('vendors.index', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
