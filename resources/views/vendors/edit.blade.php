@extends('layouts.app')

@section('title', __('vendor.edit'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        @if (request('action') == 'delete' && $vendor)
        @can('delete', $vendor)
            <div class="card">
                <div class="card-header">{{ __('vendor.delete') }}</div>
                <div class="card-body">
                    <label class="control-label text-primary">{{ __('vendor.name') }}</label>
                    <p>{{ $vendor->name }}</p>
                    <label class="control-label text-primary">{{ __('vendor.description') }}</label>
                    <p>{{ $vendor->description }}</p>
                    {!! $errors->first('vendor_id', '<span class="form-error small">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="card-body text-danger">{{ __('vendor.delete_confirm') }}</div>
                <div class="card-footer">
                    {!! FormField::delete(
                        ['route' => ['vendors.destroy', $vendor]],
                        __('app.delete_confirm_button'),
                        ['class' => 'btn btn-danger'],
                        ['vendor_id' => $vendor->id]
                    ) !!}
                    {{ link_to_route('vendors.edit', __('app.cancel'), [$vendor], ['class' => 'btn btn-link']) }}
                </div>
            </div>
        @endcan
        @else
        <div class="card">
            <div class="card-header">{{ __('vendor.edit') }}</div>
            {{ Form::model($vendor, ['route' => ['vendors.update', $vendor], 'method' => 'patch']) }}
            <div class="card-body">
                {!! FormField::text('name', ['required' => true, 'label' => __('vendor.name')]) !!}
                {!! FormField::textarea('description', ['label' => __('vendor.description')]) !!}
            </div>
            <div class="card-footer">
                {{ Form::submit(__('vendor.update'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('vendors.show', __('app.cancel'), [$vendor], ['class' => 'btn btn-link']) }}
                @can('delete', $vendor)
                    {{ link_to_route('vendors.edit', __('app.delete'), [$vendor, 'action' => 'delete'], ['class' => 'btn btn-danger float-right', 'id' => 'del-vendor-'.$vendor->id]) }}
                @endcan
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endif
@endsection
