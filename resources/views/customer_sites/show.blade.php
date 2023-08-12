@extends('layouts.app')

@section('title', __('customer_site.detail'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('customer_site.detail') }}</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        <tr><td>{{ __('customer_site.name') }}</td><td>{{ $customerSite->name }}</td></tr>
                        <tr><td>{{ __('customer_site.url') }}</td><td>{{ $customerSite->url }}</td></tr>
                        <tr><td>{{ __('app.status') }}</td><td>{{ $customerSite->is_active }}</td></tr>
                        <tr><td>{{ __('app.created_at') }}</td><td>{{ $customerSite->created_at }}</td></tr>
                        <tr><td>{{ __('app.updated_at') }}</td><td>{{ $customerSite->updated_at }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                @can('update', $customerSite)
                    {{ link_to_route('customer_sites.edit', __('customer_site.edit'), [$customerSite], ['class' => 'btn btn-warning', 'id' => 'edit-customer_site-'.$customerSite->id]) }}
                @endcan
                {{ link_to_route('customer_sites.index', __('customer_site.back_to_index'), [], ['class' => 'btn btn-link']) }}
            </div>
        </div>
    </div>
</div>
@endsection
