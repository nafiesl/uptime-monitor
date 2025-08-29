@extends('layouts.app')

@section('title', __('vendor.detail'))

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">{{ __('vendor.detail') }}</div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0 align-middle">
                                <tbody>
                                    <tr>
                                        <th class="w-25 text-muted">{{ __('vendor.name') }}</th>
                                        <td>{{ $vendor->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">{{ __('vendor.description') }}</th>
                                        <td class="text-break">{{ $vendor->description }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        @can('update', $vendor)
                            {{ link_to_route('vendors.edit', __('vendor.edit'), [$vendor], ['class' => 'btn btn-warning', 'id' => 'edit-vendor-' . $vendor->id]) }}
                        @endcan
                        {{ link_to_route('vendors.index', __('vendor.back_to_index'), [], ['class' => 'btn btn-link']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
