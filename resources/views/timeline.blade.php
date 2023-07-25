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
        <table class="table table-sm">
            <tbody>
                @foreach ($logs as $key => $monitoringLog)
                <tr>
                    <td>{{ $key++ }}</td>
                    <td>{{ $monitoringLog->url }}</td>
                    <td>{{ $monitoringLog->response_time }}</td>
                    <td>{{ $monitoringLog->status_code }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
