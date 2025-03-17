@extends('layouts.app')

@section('title', __('site.list'))

@section('content')
<div class="mb-3">
    <div class="float-end">
        @can('create', new App\Models\Site)
            {{ link_to_route('sites.create', __('site.create'), [], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
    <h2 class="page-title">{{ __('site.list') }} <small>{{ __('app.total') }} : {{ $sites->total() }} {{ __('site.site') }}</small></h2>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                {{ Form::open(['method' => 'get', 'class' => 'row row-cols-lg-auto g-2 align-items-center']) }}
                <div class="col-12">
                    {!! Form::text('q', request('q'), ['placeholder' => __('site.search')]) !!}
                </div>
                <div class="col-12">
                    {!! Form::select('vendor_id', $availableVendors, request('vendor_id'), ['placeholder' => '--'.__('vendor.all').'--']) !!}
                </div>
                <div class="col-12">
                    {{ Form::submit(__('app.search'), ['class' => 'btn btn-secondary btn-sm']) }}
                    {{ link_to_route('sites.index', __('app.reset'), [], ['class' => 'btn btn-link']) }}
                </div>
                {{ Form::close() }}
            </div>
            <table class="table table-sm table-responsive-sm table-hover">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('site.name') }}</th>
                        <th>{{ __('site.url') }}</th>
                        <th>{{ __('vendor.vendor') }}</th>
                        <th class="text-center">{{ __('app.status') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sites as $key => $site)
                    <tr>
                        <td class="text-center">{{ $sites->firstItem() + $key }}</td>
                        <td>{{ $site->name }}</td>
                        <td><a target="_blank" href="{{ $site->url }}">{{ $site->url }}</a></td>
                        <td>{{ $site->vendor->name }}</td>
                        <td class="text-center">{{ $site->is_active }}</td>
                        <td class="text-center">
                            @can('view', $site)
                                {{ link_to_route(
                                    'sites.show',
                                    __('app.show'),
                                    [$site],
                                    ['id' => 'show-site-' . $site->id]
                                ) }}
                            @endcan
                            @can('update', $site)
                                | {{ link_to_route('sites.edit', __('app.edit'), [$site], ['id' => 'edit-site-'.$site->id]) }}
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-body">{{ $sites->appends(Request::except('page'))->render() }}</div>
        </div>
    </div>
</div>
@endsection
