@extends('layouts.app')

@section('title', __('vendor.edit'))

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">

                @if (request('action') == 'delete' && $vendor)
                    @can('delete', $vendor)
                        <div class="card shadow-sm">
                            <div class="card-header">{{ __('vendor.delete') }}</div>

                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label text-primary">{{ __('vendor.name') }}</label>
                                    <p class="mb-0">{{ $vendor->name }}</p>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label text-primary">{{ __('vendor.description') }}</label>
                                    <p class="mb-0">{{ $vendor->description }}</p>
                                </div>

                                @if ($errors->has('vendor_id'))
                                    <div class="small text-danger mt-2">{{ $errors->first('vendor_id') }}</div>
                                @endif
                            </div>

                            <div class="card-body text-danger border-top">
                                {{ __('vendor.delete_confirm') }}
                            </div>

                            <div class="card-footer d-flex justify-content-between">
                                {!! FormField::delete(
                                    ['route' => ['vendors.destroy', $vendor]],
                                    __('app.delete_confirm_button'),
                                    ['class' => 'btn btn-danger'],
                                    ['vendor_id' => $vendor->id],
                                ) !!}
                                {{ link_to_route('vendors.edit', __('app.cancel'), [$vendor], ['class' => 'btn btn-link']) }}
                            </div>
                        </div>
                    @endcan
                @else
                    <div class="card shadow-sm">
                        <div class="card-header">{{ __('vendor.edit') }}</div>

                        {{ Form::model($vendor, ['route' => ['vendors.update', $vendor], 'method' => 'patch']) }}
                        <div class="card-body">
                            {!! FormField::text('name', ['required' => true, 'label' => __('vendor.name')]) !!}
                            {!! FormField::textarea('description', ['label' => __('vendor.description'), 'rows' => 4]) !!}
                        </div>

                        <div class="card-footer d-flex align-items-center gap-2">
                            {{ Form::submit(__('vendor.update'), ['class' => 'btn btn-warning']) }}
                            {{ link_to_route('vendors.show', __('app.cancel'), [$vendor], ['class' => 'btn btn-link']) }}
                            @can('delete', $vendor)
                                {{ link_to_route('vendors.edit', __('app.delete'), [$vendor, 'action' => 'delete'], ['class' => 'btn btn-danger ms-auto', 'id' => 'del-vendor-' . $vendor->id]) }}
                            @endcan
                        </div>
                        {{ Form::close() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
