@extends('layouts.site')

@section('site_content')
    <div class="pt-0">
        <div id="chart_timeline_{{ $site->id }}" class="w-100"></div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/5.3.4/apexcharts.min.css"
        integrity="sha512-IqtQ7LKr3He47p7HjxynmqZfN07VljNkdGyGDdDJ//f1r6bT0IEKQf2CCtSgun/pvbFlNnPDMRrMSQhmSxmSSg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/5.3.4/apexcharts.min.js"
        integrity="sha512-FImCCel/NA+iTEiLnRklD7hTPfcNdVEwO9niFnKmL/KoSknriTDREY7zdTTgQId1qjbcIEavsyXUlyljCql5cg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        (function() {
            // Data & rentang waktu
            const seriesData = {!! json_encode($chartData) !!};
            const startMs = {{ $startTime->timestamp * 1000 }};
            const endMs = {{ $endTime->timestamp * 1000 }};

            // Ambil warna dari Bootstrap 5 (fallback jika var tidak tersedia)
            const css = getComputedStyle(document.documentElement);
            const clrPrimary = css.getPropertyValue('--bs-primary').trim() || '#0d6efd';
            const clrWarning = css.getPropertyValue('--bs-warning').trim() || 'orange';
            const clrDanger = css.getPropertyValue('--bs-danger').trim() || 'red';
            const clrGrid = css.getPropertyValue('--bs-border-color-translucent').trim() || '#e9ecef';

            const options = {
                series: [{
                    name: 'Response time (ms)',
                    data: seriesData, // format: [[tsMs, value], ...] atau {x: tsMs, y: val}
                }],
                chart: {
                    id: 'line-datetime',
                    type: 'line',
                    height: 400,
                    animations: {
                        enabled: true
                    },
                    toolbar: {
                        show: true
                    },
                    zoom: {
                        autoScaleYaxis: true
                    }
                },
                colors: [clrPrimary],
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                markers: {
                    size: 0
                },
                grid: {
                    borderColor: clrGrid,
                    strokeDashArray: 3
                },
                annotations: {
                    yaxis: [{
                            y: {{ $site->warning_threshold }},
                            borderColor: clrWarning,
                            label: {
                                show: true,
                                text: 'Threshold',
                                style: {
                                    color: '#fff',
                                    background: clrWarning
                                }
                            }
                        },
                        {
                            y: {{ $site->down_threshold }},
                            borderColor: clrDanger,
                            label: {
                                show: true,
                                text: 'Down',
                                style: {
                                    color: '#fff',
                                    background: clrDanger
                                }
                            }
                        }
                    ]
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    type: 'datetime',
                    min: startMs,
                    max: endMs,
                    labels: {
                        datetimeUTC: false
                    },
                    title: {
                        text: 'Datetime'
                    }
                },
                yaxis: {
                    tickAmount: {{ $site->y_axis_tick_amount }},
                    title: {
                        text: 'Milliseconds'
                    },
                    max: {{ $site->y_axis_max }},
                    min: 0,
                    labels: {
                        formatter: (val) => Number(val).toLocaleString()
                    }
                },
                tooltip: {
                    shared: false,
                    x: {
                        format: 'dd MMM HH:mm:ss'
                    },
                    y: {
                        formatter: (val) => `${Number(val).toLocaleString()} ms`
                    }
                },
                noData: {
                    text: 'No data',
                    align: 'center',
                    verticalAlign: 'middle'
                },
                responsive: [{
                    breakpoint: 576,
                    options: {
                        chart: {
                            height: 280
                        }
                    }
                }]
            };

            const el = document.querySelector("#chart_timeline_{{ $site->id }}");
            const chart = new ApexCharts(el, options);
            chart.render();
        })();
    </script>
@endpush
