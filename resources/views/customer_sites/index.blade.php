@extends('layouts.app')

@section('title', __('customer_site.list'))

@section('content')
<div class="mb-3">
    <div class="float-end">
        @can('create', new App\Models\CustomerSite)
            {{ link_to_route('customer_sites.create', __('customer_site.create'), [], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
    <h2 class="page-title">{{ __('customer_site.list') }} <small>{{ __('app.total') }} : {{ $customerSites->total() }} {{ __('customer_site.customer_site') }}</small></h2>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                {{ Form::open(['method' => 'get', 'class' => 'row row-cols-lg-auto g-2 align-items-center']) }}
                <div class="col-12">
                    {!! Form::text('q', request('q'), ['placeholder' => __('customer_site.search')]) !!}
                </div>
                <div class="col-12">
                    {!! Form::select('vendor_id', $availableVendors, request('vendor_id'), ['placeholder' => '--'.__('vendor.all').'--']) !!}
                </div>
                <div class="col-12">
                    {{ Form::submit(__('app.search'), ['class' => 'btn btn-secondary btn-sm']) }}
                    {{ link_to_route('customer_sites.index', __('app.reset'), [], ['class' => 'btn btn-link']) }}
                </div>
                {{ Form::close() }}
            </div>
            <table class="table table-sm table-responsive-sm table-hover">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('customer_site.name') }}</th>
                        <th>{{ __('customer_site.url') }}</th>
                        <th>{{ __('vendor.vendor') }}</th>
                        <th>{{ __('customer_site.type') }}</th>
                        <th class="text-center">{{ __('app.status') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customerSites as $key => $customerSite)
                    <tr>
                        <td class="text-center">{{ $customerSites->firstItem() + $key }}</td>
                        <td>{{ $customerSite->name }}</td>
                        <td><a target="_blank" href="{{ $customerSite->url }}">{{ $customerSite->url }}</a></td>
                        <td>{{ $customerSite->vendor->name }}</td>
                        <td>{{ $customerSite->type->name }}</td>
                        <td class="text-center">{{ $customerSite->is_active }}</td>
                        <td class="text-center">
                            @can('view', $customerSite)
                                {{ link_to_route(
                                    'customer_sites.show',
                                    __('app.show'),
                                    [$customerSite],
                                    ['id' => 'show-customer_site-' . $customerSite->id]
                                ) }}
                            @endcan
                            @can('update', $customerSite)
                                | {{ link_to_route('customer_sites.edit', __('app.edit'), [$customerSite], ['id' => 'edit-customer_site-'.$customerSite->id]) }}
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-body">{{ $customerSites->appends(Request::except('page'))->render() }}</div>
        </div>
    </div>
</div>
@endsection
