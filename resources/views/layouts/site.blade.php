@extends('layouts.app')

@section('title', __('site.detail'))

@section('content')
    <div class="container py-4">
        <div class="row">
            {{-- Sidebar info --}}
            <div class="col-md-4 order-2 order-md-1">
                <div class="card shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="h6 mb-0">{{ __('site.site') }}</h4>
                        {!! FormField::formButton(['route' => ['sites.check_now', $site->id]], __('site.check_now'), [
                            'class' => 'btn btn-success',
                            'id' => 'check_now_' . $site->id,
                        ]) !!}
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0 align-middle">
                                <tbody>
                                    <tr>
                                        <th class="w-50 text-muted">{{ __('site.name') }}</th>
                                        <td>{{ $site->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">{{ __('site.url') }}</th>
                                        <td><a target="_blank" rel="noopener"
                                                href="{{ $site->url }}">{{ $site->url }}</a></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">{{ __('vendor.vendor') }}</th>
                                        <td>{{ $site->vendor->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">{{ __('app.status') }}</th>
                                        <td>{{ $site->is_active ? 'Active' : 'Inactive' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">{{ __('site.check_interval') }}</th>
                                        <td>{{ __('time.every') }} {{ $site->check_interval }}
                                            {{ trans_choice('time.minutes', $site->check_interval) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">{{ __('site.priority_code') }}</th>
                                        <td>{{ $site->priority_code }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">{{ __('site.warning_threshold') }}</th>
                                        <td>{{ $site->warning_threshold }} {{ __('time.miliseconds') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">{{ __('site.down_threshold') }}</th>
                                        <td>{{ $site->down_threshold }} {{ __('time.miliseconds') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">{{ __('site.notify_user_interval') }}</th>
                                        <td>{{ __('time.every') }} {{ $site->notify_user_interval }}
                                            {{ trans_choice('time.minutes', $site->notify_user_interval) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">{{ __('site.last_check_at') }}</th>
                                        <td>
                                            {{ $site->last_check_at }} <br>
                                            {{ optional($site->last_check_at)->diffForHumans() }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">{{ __('site.last_notify_user_at') }}</th>
                                        <td>
                                            {{ $site->last_notify_user_at }} <br>
                                            {{ optional($site->last_notify_user_at)->diffForHumans() }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">{{ __('app.created_at') }}</th>
                                        <td>{{ $site->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">{{ __('app.updated_at') }}</th>
                                        <td>{{ $site->updated_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        @can('update', $site)
                            {{ link_to_route('sites.edit', __('site.edit'), [$site], ['class' => 'btn btn-warning', 'id' => 'edit-site-' . $site->id]) }}
                        @endcan
                        {{ link_to_route('home', __('app.back_to_dashboard'), [], ['class' => 'btn btn-link']) }}
                    </div>
                </div>
            </div>

            {{-- Main content --}}
            <div class="col-md-8 order-1 order-md-2">
                <div class="d-md-flex align-items-start justify-content-between flex-wrap gap-2 mb-3">
                    <div class="btn-group" role="group" aria-label="Time range">
                        {{ link_to_route(Route::currentRouteName(), '1h', [$site, 'time_range' => '1h'], ['class' => 'btn btn-outline-primary px-2' . ($timeRange == '1h' ? ' active' : '')]) }}
                        {{ link_to_route(Route::currentRouteName(), '6h', [$site, 'time_range' => '6h'], ['class' => 'btn btn-outline-primary px-2' . ($timeRange == '6h' ? ' active' : '')]) }}
                        {{ link_to_route(Route::currentRouteName(), '24h', [$site, 'time_range' => '24h'], ['class' => 'btn btn-outline-primary px-2' . ($timeRange == '24h' ? ' active' : '')]) }}
                        {{ link_to_route(Route::currentRouteName(), '7d', [$site, 'time_range' => '7d'], ['class' => 'btn btn-outline-primary px-2' . ($timeRange == '7d' ? ' active' : '')]) }}
                        {{ link_to_route(Route::currentRouteName(), '14d', [$site, 'time_range' => '14d'], ['class' => 'btn btn-outline-primary px-2' . ($timeRange == '14d' ? ' active' : '')]) }}
                        {{ link_to_route(Route::currentRouteName(), '30d', [$site, 'time_range' => '30d'], ['class' => 'btn btn-outline-primary px-2' . ($timeRange == '30d' ? ' active' : '')]) }}
                        {{ link_to_route(Route::currentRouteName(), '3Mo', [$site, 'time_range' => '3Mo'], ['class' => 'btn btn-outline-primary px-2' . ($timeRange == '3Mo' ? ' active' : '')]) }}
                        {{ link_to_route(Route::currentRouteName(), '6Mo', [$site, 'time_range' => '6Mo'], ['class' => 'btn btn-outline-primary px-2' . ($timeRange == '6Mo' ? ' active' : '')]) }}
                    </div>

                    {{ Form::open(['method' => 'get', 'class' => 'row row-cols-lg-auto g-2 align-items-center']) }}
                    <div class="col">
                        {{ Form::text('start_time', $startTime->format('Y-m-d H:i'), ['class' => 'date_time_select form-control', 'style' => 'width:150px']) }}
                    </div>
                    <div class="col">
                        {{ Form::text('end_time', $endTime->format('Y-m-d H:i'), ['class' => 'date_time_select form-control', 'style' => 'width:150px']) }}
                    </div>
                    <div class="col d-flex gap-2">
                        {{ Form::submit('View Report', ['class' => 'btn btn-info']) }}
                        {{ link_to_route('sites.show', __('app.reset'), $site, ['class' => 'btn btn-secondary']) }}
                    </div>
                    {{ Form::close() }}
                </div>

                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        {{ link_to_route('sites.show', __('monitoring_log.graph'), [$site->id] + request(['time_range', 'start_time', 'end_time']), ['class' => 'nav-link ' . (in_array(Request::segment(3), [null]) ? 'active' : '')]) }}
                    </li>
                    <li class="nav-item">
                        {{ link_to_route('sites.timeline', __('monitoring_log.monitoring_log'), [$site->id] + request(['time_range', 'start_time', 'end_time']), ['class' => 'nav-link ' . (in_array(Request::segment(3), ['timeline']) ? 'active' : '')]) }}
                    </li>
                </ul>

                @yield('site_content')
            </div>
        </div>
    </div>
    <br>
@endsection

@push('styles')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css"
        integrity="sha512-f0tzWhCwVFS3WeYaofoLWkTP62ObhewQ1EZn65oSYDZUg1+CyywGKkWzm8BxaJj5HGKI72PnMH9jYyIFz+GH7g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.js"
        integrity="sha512-L7jgg7T9UbYc7hXogUKssqe1B5MsgrcviNxsRbO53dDSiw/JxuA/4kVQvEORmZJ6Re3fVF3byN5TT7czo9Rdug=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.date_time_select').datetimepicker({
                format: 'Y-m-d H:i',
                closeOnTimeSelect: true,
                scrollInput: false,
                dayOfWeekStart: 1
            });
        });
    </script>
@endpush
