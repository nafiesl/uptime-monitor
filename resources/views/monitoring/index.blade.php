@extends('layouts.app')

@section('content')

<div class="page-header mt-0 mb-4">
    <h1 class="page-title">Uptime Monitor</h1>
</div>

<div class="row mb-4">
    @foreach ($customerSites as $customerSite)
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    @can('view', $customerSite)
                        {{ link_to_route(
                            'customer_sites.show',
                            __('app.show'),
                            [$customerSite, 'time_range' => '6h'],
                            ['id' => 'show-customer_site-' . $customerSite->id, 'class' => 'float-end']
                        ) }}
                    @endcan
                    {{ $customerSite->name }}
                </div>
                <div class="card-body">
                    <ul class="pl-1">
                        <li>URL: {{ $customerSite->url }}</li>
                    </ul>
                    <hr>
                    <div id="chart_timeline_{{ $customerSite->id }}"></div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    @foreach ($customerSites as $customerSite)
        @php
            $monitoringLogs = $customerSite->monitoringLogs()
                ->whereBetween('created_at', [Carbon::now()->subHours(6), Carbon::now()])
                ->get(['response_time', 'created_at']);
            $chartData = [];
            foreach ($monitoringLogs as $monitoringLog) {
                $chartData[] = ['x' => $monitoringLog->created_at, 'y' => $monitoringLog->response_time];
            }
        @endphp
        var options = {
            series: [{
                data: {!! json_encode($chartData) !!}
            }],
            chart: {
                id: 'line-datetime',
                type: 'line',
                height: 250,
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
                min: new Date("{{ Carbon::now()->subHours(6)->format('Y-m-d H:i:s') }}").getTime(),
                max: new Date("{{ Carbon::now()->format('Y-m-d H:i:s') }}").getTime(),
                labels: {
                    datetimeUTC: false,
                },
                title: {
                    text: 'Datetime',
                },
            },
            yaxis: {
                tickAmount: 6,
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

        var chart = new ApexCharts(document.querySelector("#chart_timeline_{{ $customerSite->id }}"), options);
        chart.render();
    @endforeach
</script>
@endpush
