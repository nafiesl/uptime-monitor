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
        <h1>Uptime Monitor</h1>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>URL</th>
                            <th>Response Time</th>
                            <th>Status Code</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $key => $monitoringLog)
                        <tr>
                            <td class="text-center">{{ ++$key }}</td>
                            <td>{{ $monitoringLog->url }}</td>
                            <td class="text-right">{{ number_format($monitoringLog->response_time, 0) }}</td>
                            <td class="text-center">{{ $monitoringLog->status_code }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</body>
</html>
