@extends('layouts.app')

@section('title', __('vendor.list'))

@section('content')
    <div class="container py-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <h2 class="h4 mb-0">
                {{ __('vendor.list') }}
                <small class="text-muted">
                    {{ __('app.total') }} : {{ $vendors->total() }} {{ __('vendor.vendor') }}
                </small>
            </h2>

            @can('create', new App\Models\Vendor())
                {{ link_to_route('vendors.create', __('vendor.create'), [], ['class' => 'btn btn-success']) }}
            @endcan
        </div>

        <div class="card shadow-sm">
            <div class="card-header">
                {{ Form::open(['method' => 'get', 'class' => 'row row-cols-lg-auto g-3 align-items-center']) }}
                <div class="col-12">
                    {!! Form::text('q', request('q'), [
                        'label' => false,
                        'placeholder' => __('vendor.search'),
                        'class' => 'form-control',
                    ]) !!}
                </div>
                <div class="col-12 d-flex gap-2">
                    {{ Form::submit(__('vendor.search'), ['class' => 'btn btn-primary btn-sm']) }}
                    {{ link_to_route('vendors.index', __('app.reset'), [], ['class' => 'btn btn-secondary btn-sm']) }}
                </div>
                {{ Form::close() }}
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:72px">{{ __('app.table_no') }}</th>
                            <th style="width:25%">{{ __('vendor.name') }}</th>
                            <th>{{ __('vendor.description') }}</th>
                            <th class="text-center" style="width:120px">{{ __('app.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendors as $key => $vendor)
                            <tr>
                                <td class="text-center">{{ $vendors->firstItem() + $key }}</td>
                                <td>{{ $vendor->name }}</td> {{-- amankan output --}}
                                <td class="text-break">{{ $vendor->description }}</td>
                                <td class="text-center">
                                    @can('view', $vendor)
                                        <a href="{{ route('vendors.show', $vendor) }}" id="show-vendor-{{ $vendor->id }}">
                                            {{ __('app.show') }}
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-body">
                {{ $vendors->appends(Request::except('page'))->render() }}
            </div>
        </div>
    </div>
@endsection
