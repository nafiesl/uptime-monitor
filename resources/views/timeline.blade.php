@extends('layouts.app')

@section('content')
<div class="page-header mt-0 mb-4">
    <h1 class="page-title">Uptime Monitor</h1>
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'row row-cols-lg-auto g-3 align-items-center']) }}
            <label for="customer_site_id">Customer Site</label>
            <div class="col-12">
                {{ Form::select('customer_site_id', $customerSites, request('customer_site_id'), ['placeholder' => '-- All sites --', 'class' => 'form-select']) }}
            </div>
            <div class="col-12">
                {{ Form::submit('View Report', ['class' => 'btn btn-info mr-1']) }}
                {{ link_to_route('timeline', 'Reset', [], ['class' => 'btn btn-secondary mr-1']) }}
            </div>
        {{ Form::close() }}
    </div>
</div>

<div class="card">
    <div class="card-body">
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
        {{ $logs->appends(Request::except('page'))->links() }}
    </div>
</div>

@endsection
