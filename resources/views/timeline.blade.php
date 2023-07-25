<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Uptime Monitor</title>
    <link rel="stylesheet" href="">
</head>
<body>
    <h1>Uptime Monitor</h1>
    <table class="table table-sm">
        <tbody>
            @foreach ($logs as $key => $monitoringLog)
                <td>{{ $key++ }}</td>
                <td>{{ $monitoringLog->url }}</td>
                <td>{{ $monitoringLog->response_time }}</td>
                <td>{{ $monitoringLog->status_code }}</td>
            @endforeach
        </tbody>
    </table>
</body>
</html>
