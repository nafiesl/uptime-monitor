@extends('layouts.app')

@section('content')

<div class="page-header mt-0 mb-4">
    <h1 class="page-title">Uptime Monitor</h1>
</div>

<div class="card">
    <div class="card-body">
        <div id="uptime_chart"></div>
    </div>
</div>

@foreach ($customerSites->chunk(3) as $chunkedCustomerSites)
    <div class="row mb-4">
        @foreach ($chunkedCustomerSites as $customerSite)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">{{ $customerSite->name }}</div>
                    <div class="card-body">
                        <ul class="pl-1">
                            <li>URL: {{ $customerSite->url }}</li>
                        </ul>
                        <hr>
                        <h5>Latest logs</h5>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th class="text-center">Status</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customerSite->latestLogs()->limit(5)->get() as $monitoringLog)
                                <tr>
                                    <td>{{ number_format($monitoringLog->response_time, 0) }}</td>
                                    <td class="text-center">{{ $monitoringLog->status_code }}</td>
                                    <td>{{ $monitoringLog->created_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endforeach
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var options = {
        series: [{
            name: "Desktops",
            data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
        }],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'straight'
        },
        title: {
            text: 'Uptime Monitor',
            align: 'left'
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'],
                opacity: 0.5
            },
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
        }
    };

    var uptime_chart = new ApexCharts(document.querySelector("#uptime_chart"), options);
    uptime_chart.render();
</script>
@endpush
