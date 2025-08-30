@extends('layouts.app')

@section('title', __('site.list'))

@section('content')
    <div class="container py-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <h2 class="h4 mb-0">
                {{ __('site.list') }}
                <small class="text-muted">
                    {{ __('app.total') }} : {{ $sites->total() }} {{ __('site.site') }}
                </small>
            </h2>

            @can('create', new App\Models\Site())
                {{ link_to_route('sites.create', __('site.create'), [], ['class' => 'btn btn-success']) }}
            @endcan
        </div>

        <div class="card shadow-sm">
            <div class="card-header">
                {{ Form::open(['method' => 'get', 'class' => 'row row-cols-lg-auto g-2 align-items-center']) }}
                <div class="col-12">
                    {!! Form::text('q', request('q'), [
                        'placeholder' => __('site.search'),
                        'class' => 'form-control',
                    ]) !!}
                </div>
                <div class="col-12">
                    {!! Form::select('vendor_id', $availableVendors, request('vendor_id'), [
                        'placeholder' => '--' . __('vendor.all') . '--',
                        'class' => 'form-select',
                    ]) !!}
                </div>
                <div class="col-12 d-flex gap-2">
                    {{ Form::submit(__('app.search'), ['class' => 'btn btn-primary btn-sm']) }}
                    {{ link_to_route('sites.index', __('app.reset'), [], ['class' => 'btn btn-secondary btn-sm']) }}
                </div>
                {{ Form::close() }}
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:72px">{{ __('app.table_no') }}</th>
                            <th>{{ __('site.name') }}</th>
                            <th>{{ __('site.url') }}</th>
                            <th>{{ __('vendor.vendor') }}</th>
                            <th class="text-center">{{ __('app.status') }}</th>
                            <th class="text-center" style="width:160px">{{ __('app.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sites as $key => $site)
                            <tr>
                                <td class="text-center">{{ $sites->firstItem() + $key }}</td>
                                <td>{{ $site->name }}</td>
                                <td>
                                    <a target="_blank" rel="noopener" href="{{ $site->url }}">{{ $site->url }}</a>
                                </td>
                                <td>{{ $site->vendor->name }}</td>
                                <td class="text-center">{{ $site->is_active ? 'Active' : 'Inactive' }}</td>
                                <td class="text-center">
                                    @can('view', $site)
                                        {{ link_to_route('sites.show', __('app.show'), [$site], ['id' => 'show-site-' . $site->id]) }}
                                    @endcan
                                    @can('update', $site)
                                        <span class="mx-1 text-muted">|</span>
                                        {{ link_to_route('sites.edit', __('app.edit'), [$site], ['id' => 'edit-site-' . $site->id]) }}
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-body">
                {{ $sites->appends(Request::except('page'))->render() }}
            </div>
        </div>
    </div>
@endsection
