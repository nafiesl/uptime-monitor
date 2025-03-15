@extends('layouts.site')

@section('site_content')
<div class="pt-0">
    <div id="chart_timeline_{{ $site->id }}"></div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var options = {
        series: [{
            name: 'Response time (ms)',
            data: {!! json_encode($chartData) !!},
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
                y: {{ $site->warning_threshold }},
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
                y: {{ $site->down_threshold }},
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
            tickAmount: {{ $site->y_axis_tick_amount }},
            title: {
                text: 'Miliseconds',
            },
            max: {{ $site->y_axis_max }},
            min: 0,
        },
        stroke: {
          width: [2]
        },
        tooltip: {
            x: {
                format: 'dd MMM HH:mm:ss'
            }
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart_timeline_{{ $site->id }}"), options);
    chart.render();
</script>
@endpush
