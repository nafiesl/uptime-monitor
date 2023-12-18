@extends('layouts.app')

@section('title', __('vendor.list'))

@section('content')
<div class="mb-3">
    <div class="float-end">
        @can('create', new App\Models\Vendor)
            <a href="{{ route('vendors.create') }}" class="btn btn-success">{{ __('vendor.create') }}</a>
        @endcan
    </div>
    <h2 class="page-title">{{ __('vendor.list') }} <small>{{ __('app.total') }} : {{ $vendors->total() }} {{ __('vendor.vendor') }}</small></h2>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <form method="GET" action="" accept-charset="UTF-8">
                    <div class="row g-2">
                        <div class="col-auto">
                            <label for="q" class="col-form-label">{{ __('vendor.search') }}</label>
                        </div>
                        <div class="col-auto">
                            <input placeholder="{{ __('vendor.search_text') }}" name="q" type="text" id="q" class="form-control" value="{{ request('q') }}">
                        </div>
                        <div class="col-auto">
                            <input type="submit" value="{{ __('vendor.search') }}" class="btn btn-secondary">
                            <a href="{{ route('vendors.index') }}" class="btn btn-link">{{ __('app.reset') }}</a>
                        </div>
                    </div>
                </form>
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
