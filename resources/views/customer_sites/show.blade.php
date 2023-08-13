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
<br>
<div class="card">
    <div class="card-body">
        <div class="btn-group" role="group">
            {{ link_to_route('customer_sites.show', '1h', [$customerSite, 'start_timestamp' => Carbon::now()->subHours(1)->timestamp, 'end_timestamp' => Carbon::now()->timestamp], ['class' => 'btn btn-outline-primary']) }}
            {{ link_to_route('customer_sites.show', '6h', [$customerSite, 'start_timestamp' => Carbon::now()->subHours(6)->timestamp, 'end_timestamp' => Carbon::now()->timestamp], ['class' => 'btn btn-outline-primary']) }}
            {{ link_to_route('customer_sites.show', '24h', [$customerSite, 'start_timestamp' => Carbon::now()->subHours(24)->timestamp, 'end_timestamp' => Carbon::now()->timestamp], ['class' => 'btn btn-outline-primary']) }}
            {{ link_to_route('customer_sites.show', '7d', [$customerSite, 'start_timestamp' => Carbon::now()->subDays(7)->timestamp, 'end_timestamp' => Carbon::now()->timestamp], ['class' => 'btn btn-outline-primary']) }}
            {{ link_to_route('customer_sites.show', '14d', [$customerSite, 'start_timestamp' => Carbon::now()->subDays(14)->timestamp, 'end_timestamp' => Carbon::now()->timestamp], ['class' => 'btn btn-outline-primary']) }}
            {{ link_to_route('customer_sites.show', '30d', [$customerSite, 'start_timestamp' => Carbon::now()->subDays(30)->timestamp, 'end_timestamp' => Carbon::now()->timestamp], ['class' => 'btn btn-outline-primary']) }}
            {{ link_to_route('customer_sites.show', '3Mo', [$customerSite, 'start_timestamp' => Carbon::now()->subMonths(3)->timestamp, 'end_timestamp' => Carbon::now()->timestamp], ['class' => 'btn btn-outline-primary']) }}
            {{ link_to_route('customer_sites.show', '6Mo', [$customerSite, 'start_timestamp' => Carbon::now()->subMonths(6)->timestamp, 'end_timestamp' => Carbon::now()->timestamp], ['class' => 'btn btn-outline-primary']) }}
        </div>
        <div id="chart_timestampline_{{ $customerSite->id }}"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
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
                y: 5000,
                borderColor: 'orange',
                label: {
                    show: true,
                    text: 'Trashold',
                    style: {
                        color: "#fff",
                        background: 'orange'
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
            tickAmount: 10,
            title: {
                text: 'Miliseconds',
            },
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

    var chart = new ApexCharts(document.querySelector("#chart_timestampline_{{ $customerSite->id }}"), options);
    chart.render();
</script>
@endpush
