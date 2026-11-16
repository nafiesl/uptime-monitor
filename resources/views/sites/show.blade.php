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
        }, {
            name: 'Status code',
            data: {!! json_encode($statusChartData) !!},
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
        yaxis: [{
            tickAmount: {{ $site->y_axis_tick_amount }},
            title: {
                text: 'Miliseconds',
            },
            max: {{ $site->y_axis_max }},
            min: 0,
        }, {
            seriesName: 'Status code',
            opposite: true,
            tickAmount: 6,
            max: 600,
            min: 0,
            title: {
                text: 'Status Code',
            },
            labels: {
                formatter: function (value) {
                    return Math.round(value);
                }
            }
        }],
        stroke: {
          width: [2, 2]
        },
        tooltip: {
            shared: true,
            x: {
                format: 'dd MMM HH:mm:ss'
            },
            y: {
                formatter: function (value, opts) {
                    return opts.seriesIndex === 0 ? value + ' ms' : value;
                }
            }
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart_timeline_{{ $site->id }}"), options);
    chart.render();
</script>
@endpush
