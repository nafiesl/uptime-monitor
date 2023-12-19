@extends('layouts.customer_site')

@section('customer_site_content')
<div class="pt-2">
    <table class="table table-sm" style="max-width: 40em">
        <thead>
            <tr>
                <th class="text-center">{{ __('app.table_no') }}</th>
                <th>{{ __('app.created_at') }}</th>
                <th class="text-center">{{ __('monitoring_log.status_code') }}</th>
                <th class="text-end">{{ __('monitoring_log.response_time') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($monitoringLogs as $key => $monitoringLog)
            <tr>
                <td class="text-center">{{ $monitoringLogs->firstItem() + $key }}</td>
                <td>{{ $monitoringLog->created_at }}</td>
                <td class="text-center">{{ $monitoringLog->status_code }}</td>
                <td class="text-end">{{ number_format($monitoringLog->response_time, 0) }} {{ __('time.miliseconds') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $monitoringLogs->appends(Request::except('page'))->links() }}
</div>
@endsection
