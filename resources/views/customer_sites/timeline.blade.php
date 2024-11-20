@extends('layouts.customer_site')

@section('customer_site_content')
<div class="pt-2">
    <table class="table table-sm">
        <thead>
            <tr>
                <th class="text-center">{{ __('app.table_no') }}</th>
                <th class="col-3">{{ __('app.created_at') }}</th>
                <th class="col-2 text-center">{{ __('monitoring_log.status_code') }}</th>
                <th class="col-2 text-end">{{ __('monitoring_log.response_time') }}</th>
                <th class="col-5">{{ __('monitoring_log.response_message') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($monitoringLogs as $key => $monitoringLog)
            <tr>
                <td class="text-center">{{ $monitoringLogs->firstItem() + $key }}</td>
                <td>{{ $monitoringLog->created_at }}</td>
                <td class="text-center">{{ $monitoringLog->status_code }}</td>
                <td class="text-end">{{ number_format($monitoringLog->response_time, 0) }} {{ __('time.miliseconds') }}</td>
                <td>{{ $monitoringLog->response_message }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $monitoringLogs->appends(Request::except('page'))->links() }}
</div>
@endsection
