@extends('layouts.app')

@section('title', __('vendor.detail'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('vendor.detail') }}</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        <tr><td>{{ __('vendor.name') }}</td><td>{{ $vendor->name }}</td></tr>
                        <tr><td>{{ __('vendor.description') }}</td><td>{{ $vendor->description }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                @can('update', $vendor)
                    {{ link_to_route('vendors.edit', __('vendor.edit'), [$vendor], ['class' => 'btn btn-warning', 'id' => 'edit-vendor-'.$vendor->id]) }}
                @endcan
                {{ link_to_route('vendors.index', __('vendor.back_to_index'), [], ['class' => 'btn btn-link']) }}
            </div>
        </div>
    </div>
</div>
@endsection
