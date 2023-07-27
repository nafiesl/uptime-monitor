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
            <div class="page-options d-flex">
                {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
                {{ Form::label('customer_site_id', 'Customer Site', ['class' => 'control-label mr-1']) }}
                {{ Form::select('customer_site_id', $customerSites->prepend('-- All sites --'), request('customer_site_id'), ['class' => 'form-control mr-1']) }}
                <div class="form-group mt-4 mt-sm-0">
                    {{ Form::submit('View Report', ['class' => 'btn btn-info mr-1']) }}
                    {{ link_to_route('timeline', 'Reset', [], ['class' => 'btn btn-secondary mr-1']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>

        <table class="table table-sm">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Customer</th>
                    <th>URL</th>
                    <th class="text-right">Response Time</th>
                    <th class="text-center">Status Code</th>
                    <th>Created at</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $key => $monitoringLog)
                <tr>
                    <td class="text-center">{{ ++$key }}</td>
                    <td>{{ $monitoringLog->customerSite->name }}</td>
                    <td>{{ $monitoringLog->url }}</td>
                    <td class="text-right">{{ number_format($monitoringLog->response_time, 0) }}</td>
                    <td class="text-center">{{ $monitoringLog->status_code }}</td>
                    <td>{{ $monitoringLog->created_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $logs->links() }}
    </div>
</body>
</html>
