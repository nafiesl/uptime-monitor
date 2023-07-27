<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Uptime Monitor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="page-header mt-0 mb-4">
            <h1 class="page-title">Uptime Monitor</h1>
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
                                        @foreach ($customerSite->latestLogs as $monitoringLog)
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
    </div>
</body>
</html>
