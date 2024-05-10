@extends('layouts.app')

@section('title', __('customer_site.detail'))

@section('content')
<div class="row">
    <div class="col-md-4 order-2 order-md-1">
        <div class="card">
            <div class="card-header">
                @auth
                <div class="float-end">
                    {!! FormField::formButton(
                        ['route' => ['customer_sites.check_now', $customerSite->id]],
                        __('customer_site.check_now'),
                        ['class' => 'btn btn-success', 'id' => 'check_now_'.$customerSite->id]
                        ) !!}
                </div>
                @endauth
                <h4 class="">{{ __('customer_site.customer_site') }}</h4>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        <tr><td>{{ __('customer_site.name') }}</td><td>{{ $customerSite->name }}</td></tr>
                        @auth
                        <tr><td>{{ __('customer_site.url') }}</td><td><a target="_blank" href="{{ $customerSite->url }}">{{ $customerSite->url }}</a></td></tr>
                        @endauth
                        <tr><td>{{ __('vendor.vendor') }}</td><td>{{ $customerSite->vendor->name }}</td></tr>
                        <tr><td>{{ __('customer_site.type') }}</td><td>{{ $customerSite->type->name }}</td></tr>
                        <tr><td>{{ __('app.status') }}</td><td>{{ $customerSite->is_active }}</td></tr>
                        <tr>
                            <td>{{ __('customer_site.check_interval') }}</td>
                            <td>
                                {{ __('time.every') }}
                                {{ $customerSite->check_interval }}
                                {{ trans_choice('time.minutes', $customerSite->check_interval) }}
                            </td>
                        </tr>
                        <tr><td>{{ __('customer_site.priority_code') }}</td><td>{{ $customerSite->priority_code }}</td></tr>
                        <tr><td>{{ __('customer_site.warning_threshold') }}</td><td>{{ $customerSite->warning_threshold }} {{ __('time.miliseconds') }}</td></tr>
                        <tr><td>{{ __('customer_site.down_threshold') }}</td><td>{{ $customerSite->down_threshold }} {{ __('time.miliseconds') }}</td></tr>
                        <tr>
                            <td>{{ __('customer_site.notify_user_interval') }}</td>
                            <td>
                                {{ __('time.every') }}
                                {{ $customerSite->notify_user_interval }}
                                {{ trans_choice('time.minutes', $customerSite->notify_user_interval) }}
                            </td>
                        </tr>
                        <tr><td>{{ __('customer_site.last_check_at') }}</td><td>{{ optional($customerSite->last_check_at)->diffForHumans() }}</td></tr>
                        <tr><td>{{ __('customer_site.last_notify_user_at') }}</td><td>{{ optional($customerSite->last_notify_user_at)->diffForHumans() }}</td></tr>
                        <tr><td>{{ __('app.created_at') }}</td><td>{{ $customerSite->created_at }}</td></tr>
                        <tr><td>{{ __('app.updated_at') }}</td><td>{{ $customerSite->updated_at }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                @can('update', $customerSite)
                    {{ link_to_route('customer_sites.edit', __('customer_site.edit'), [$customerSite], ['class' => 'btn btn-warning', 'id' => 'edit-customer_site-'.$customerSite->id]) }}
                @endcan
                @auth
                {{ link_to_route('home', __('app.back_to_dashboard'), [], ['class' => 'btn btn-link']) }}
                @endauth
            </div>
        </div>
    </div>
    <div class="col-md-8 order-1 order-md-2">
        <div class="py-4 py-md-0 clearfix">
            <div class="btn-group mb-3" role="group">
                {{ link_to_route(Route::currentRouteName(), '1h', [$customerSite, 'time_range' => '1h'], ['class' => 'px-2 btn btn-outline-primary'.($timeRange == '1h' ? ' active' : '')]) }}
                {{ link_to_route(Route::currentRouteName(), '6h', [$customerSite, 'time_range' => '6h'], ['class' => 'px-2 btn btn-outline-primary'.($timeRange == '6h' ? ' active' : '')]) }}
                {{ link_to_route(Route::currentRouteName(), '24h', [$customerSite, 'time_range' => '24h'], ['class' => 'px-2 btn btn-outline-primary'.($timeRange == '24h' ? ' active' : '')]) }}
                {{ link_to_route(Route::currentRouteName(), '7d', [$customerSite, 'time_range' => '7d'], ['class' => 'px-2 btn btn-outline-primary'.($timeRange == '7d' ? ' active' : '')]) }}
                {{ link_to_route(Route::currentRouteName(), '14d', [$customerSite, 'time_range' => '14d'], ['class' => 'px-2 btn btn-outline-primary'.($timeRange == '14d' ? ' active' : '')]) }}
                {{ link_to_route(Route::currentRouteName(), '30d', [$customerSite, 'time_range' => '30d'], ['class' => 'px-2 btn btn-outline-primary'.($timeRange == '30d' ? ' active' : '')]) }}
                {{ link_to_route(Route::currentRouteName(), '3Mo', [$customerSite, 'time_range' => '3Mo'], ['class' => 'px-2 btn btn-outline-primary'.($timeRange == '3Mo' ? ' active' : '')]) }}
                {{ link_to_route(Route::currentRouteName(), '6Mo', [$customerSite, 'time_range' => '6Mo'], ['class' => 'px-2 btn btn-outline-primary'.($timeRange == '6Mo' ? ' active' : '')]) }}
            </div>
            <div class="float-end">
                {{ Form::open(['method' => 'get', 'class' => 'row row-cols-lg-auto g-2 align-items-center']) }}
                <div class="col">
                    {{ Form::text('start_time', $startTime->format('Y-m-d H:i'), ['class' => 'date_time_select form-control', 'style' => 'width:150px']) }}
                </div>
                <div class="col">
                    {{ Form::text('end_time', $endTime->format('Y-m-d H:i'), ['class' => 'date_time_select form-control', 'style' => 'width:150px']) }}
                </div>
                <div class="col">
                    {{ Form::submit('View Report', ['class' => 'btn btn-info mr-1']) }}
                    {{ link_to_route('customer_sites.show', __('app.reset'), $customerSite, ['class' => 'btn btn-secondary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>

        @auth 
        <ul class="nav nav-tabs">
            <li class="nav-item">
                {{ link_to_route('customer_sites.show', __('monitoring_log.graph'), [$customerSite->id]+request(['time_range', 'start_time', 'end_time']), ['class' => 'nav-link '.(in_array(Request::segment(3), [null]) ? 'active' : '')]) }}
            </li>
            <li class="nav-item">
                {{ link_to_route('customer_sites.timeline', __('monitoring_log.monitoring_log'), [$customerSite->id]+request(['time_range', 'start_time', 'end_time']), ['class' => 'nav-link '.(in_array(Request::segment(3), ['timeline']) ? 'active' : '')]) }}
            </li>
        </ul>
        @else
        <ul class="nav nav-tabs">
            <li class="nav-item">
                {{ link_to_route('customer_sites.public-show', __('monitoring_log.graph'), [$customerSite->id]+request(['time_range', 'start_time', 'end_time']), ['class' => 'nav-link '.(in_array(Request::segment(4), [null]) ? 'active' : '')]) }}
            </li>
            <li class="nav-item">
                {{ link_to_route('customer_sites.public-timeline', __('monitoring_log.monitoring_log'), [$customerSite->id]+request(['time_range', 'start_time', 'end_time']), ['class' => 'nav-link '.(in_array(Request::segment(3), ['timeline']) ? 'active' : '')]) }}
            </li>
        </ul>
        @endauth

        @yield('customer_site_content')
    </div>
</div>
<br>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ url('css/plugins/jquery.datetimepicker.css') }}">
@endpush

@push('scripts')
<script src="{{ url('js/jquery.min.js') }}"></script>
<script src="{{ url('js/plugins/jquery.datetimepicker.js') }}"></script>
<script>
    $('.date_time_select').datetimepicker({
        format:'Y-m-d H:i',
        closeOnTimeSelect: true,
        scrollInput: false,
        dayOfWeekStart: 1
    });
</script>
@endpush
