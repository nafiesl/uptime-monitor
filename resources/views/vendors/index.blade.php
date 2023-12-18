@extends('layouts.app')

@section('title', __('vendor.list'))

@section('content')
<div class="mb-3">
    <div class="float-end">
        @can('create', new App\Models\Vendor)
            {{ link_to_route('vendors.create', __('vendor.create'), [], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
    <h2 class="page-title">{{ __('vendor.list') }} <small>{{ __('app.total') }} : {{ $vendors->total() }} {{ __('vendor.vendor') }}</small></h2>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                {{ Form::open(['method' => 'get', 'class' => 'row row-cols-lg-auto g-3 align-items-center']) }}
                <div class="col-12">
                    {!! Form::text('q', request('q'), ['label' => false, 'placeholder' => __('vendor.search')]) !!}
                </div>
                <div class="col-12">
                    {{ Form::submit(__('vendor.search'), ['class' => 'btn-secondary']) }}
                    {{ link_to_route('vendors.index', __('app.reset'), [], ['class' => 'btn btn-link']) }}
                </div>
                {{ Form::close() }}
            </div>
            <table class="table table-sm table-responsive-sm table-hover">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('vendor.name') }}</th>
                        <th>{{ __('vendor.description') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendors as $key => $vendor)
                    <tr>
                        <td class="text-center">{{ $vendors->firstItem() + $key }}</td>
                        <td>{!! $vendor->name !!}</td>
                        <td>{{ $vendor->description }}</td>
                        <td class="text-center">
                            @can('view', $vendor)
                                <a href="{{ route('vendors.show', $vendor) }}" id="show-vendor-{{ $vendor->id }}">{{ __('app.show') }}</a>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-body">{{ $vendors->appends(Request::except('page'))->render() }}</div>
        </div>
    </div>
</div>
@endsection
