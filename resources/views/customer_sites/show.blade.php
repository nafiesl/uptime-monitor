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
                        <tr><td>{{ __('customer_site.check_interval') }}</td><td>{{ __('time.every') }} {{ $customerSite->check_interval }} {{ __('time.minutes') }}</td></tr>
                        <tr><td>{{ __('customer_site.priority_code') }}</td><td>{{ $customerSite->priority_code }}</td></tr>
                        <tr><td>{{ __('customer_site.warning_threshold') }}</td><td>{{ $customerSite->warning_threshold }} {{ __('time.miliseconds') }}</td></tr>
                        <tr><td>{{ __('customer_site.down_threshold') }}</td><td>{{ $customerSite->down_threshold }} {{ __('time.miliseconds') }}</td></tr>
                        <tr><td>{{ __('customer_site.notify_user_interval') }}</td><td>{{ __('time.every') }} {{ $customerSite->notify_user_interval }} {{ __('time.minutes') }}</td></tr>
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
                {{ link_to_route('customer_sites.index', __('customer_site.back_to_index'), [], ['class' => 'btn btn-link']) }}
            </div>
        </div>
    </div>
</div>
<br>
<div class="card">
    <div class="card-body pb-0">
        <div class="btn-group mb-3" role="group">
            {{ link_to_route('customer_sites.show', '1h', [$customerSite, 'time_range' => '1h'], ['class' => 'px-2 btn btn-outline-primary'.($timeRange == '1h' ? ' active' : '')]) }}
            {{ link_to_route('customer_sites.show', '6h', [$customerSite, 'time_range' => '6h'], ['class' => 'px-2 btn btn-outline-primary'.($timeRange == '6h' ? ' active' : '')]) }}
            {{ link_to_route('customer_sites.show', '24h', [$customerSite, 'time_range' => '24h'], ['class' => 'px-2 btn btn-outline-primary'.($timeRange == '24h' ? ' active' : '')]) }}
            {{ link_to_route('customer_sites.show', '7d', [$customerSite, 'time_range' => '7d'], ['class' => 'px-2 btn btn-outline-primary'.($timeRange == '7d' ? ' active' : '')]) }}
            {{ link_to_route('customer_sites.show', '14d', [$customerSite, 'time_range' => '14d'], ['class' => 'px-2 btn btn-outline-primary'.($timeRange == '14d' ? ' active' : '')]) }}
            {{ link_to_route('customer_sites.show', '30d', [$customerSite, 'time_range' => '30d'], ['class' => 'px-2 btn btn-outline-primary'.($timeRange == '30d' ? ' active' : '')]) }}
            {{ link_to_route('customer_sites.show', '3Mo', [$customerSite, 'time_range' => '3Mo'], ['class' => 'px-2 btn btn-outline-primary'.($timeRange == '3Mo' ? ' active' : '')]) }}
            {{ link_to_route('customer_sites.show', '6Mo', [$customerSite, 'time_range' => '6Mo'], ['class' => 'px-2 btn btn-outline-primary'.($timeRange == '6Mo' ? ' active' : '')]) }}
        </div>
        <div class="float-end">
            {{ Form::open(['method' => 'get', 'class' => 'row row-cols-lg-auto g-3 align-items-center']) }}
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
    <div class="card-body pt-0">
        <div id="chart_timeline_{{ $customerSite->id }}"></div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ url('css/plugins/jquery.datetimepicker.css') }}">
@endpush

@push('scripts')
<script src="{{ url('js/jquery.min.js') }}"></script>
<script src="{{ url('js/plugins/jquery.datetimepicker.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $('.date_time_select').datetimepicker({
        format:'Y-m-d H:i',
        closeOnTimeSelect: true,
        scrollInput: false,
        dayOfWeekStart: 1
    });
    var options = {
        series: [{
            data: {!! json_encode($chartData) !!}
        }],
        chart: {
            id: 'line-datetime',
            type: 'line',
            height: 400,
            zoom: {
                autoScaleYaxis: true
            }
        },
        annotations: {
            yaxis: [{
                y: {{ $customerSite->warning_threshold }},
                borderColor: 'orange',
                label: {
                    show: true,
                    text: 'Threshold',
                    style: {
                        color: "#fff",
                        background: 'orange'
                    }
                }
            }, {
                y: {{ $customerSite->down_threshold }},
                borderColor: 'red',
                label: {
                    show: true,
                    text: 'Down',
                    style: {
                        color: "#fff",
                        background: 'red'
                    }
                }
            }]
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            type: 'datetime',
            min: new Date("{{ $startTime->format('Y-m-d H:i:s') }}").getTime(),
            max: new Date("{{ $endTime->format('Y-m-d H:i:s') }}").getTime(),
            labels: {
                datetimeUTC: false,
            },
            title: {
                text: 'Datetime',
            },
        },
        yaxis: {
            tickAmount: {{ $customerSite->y_axis_tick_amount }},
            title: {
                text: 'Miliseconds',
            },
            max: {{ $customerSite->y_axis_max }},
            min: 0,
        },
        stroke: {
          width: [2]
        },
        tooltip: {
            x: {
                format: 'dd MMM yyyy'
            }
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart_timeline_{{ $customerSite->id }}"), options);
    chart.render();
</script>
@endpush
