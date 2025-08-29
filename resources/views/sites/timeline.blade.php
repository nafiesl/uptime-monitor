@extends('layouts.site')

@section('site_content')
    <div class="pt-2">
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width:72px">{{ __('app.table_no') }}</th>
                        <th class="text-nowrap" style="width:20%">{{ __('app.created_at') }}</th>
                        <th class="text-center" style="width:12%">{{ __('monitoring_log.status_code') }}</th>
                        <th class="text-end" style="width:15%">{{ __('monitoring_log.response_time') }}</th>
                        <th>{{ __('monitoring_log.response_message') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($monitoringLogs as $key => $monitoringLog)
                        @php
                            $code = (int) $monitoringLog->status_code;
                            $badge =
                                $code >= 500
                                    ? 'danger'
                                    : ($code >= 400
                                        ? 'warning'
                                        : ($code >= 300
                                            ? 'info'
                                            : ($code >= 200
                                                ? 'success'
                                                : 'secondary')));
                        @endphp
                        <tr>
                            <td class="text-center">{{ $monitoringLogs->firstItem() + $key }}</td>
                            <td class="text-nowrap">
                                {{ $monitoringLog->created_at }}
                                <div class="small text-muted">
                                    {{ optional($monitoringLog->created_at)->diffForHumans() }}
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $badge }}">{{ $code }}</span>
                            </td>
                            <td class="text-end">
                                {{ number_format($monitoringLog->response_time, 0) }} {{ __('time.miliseconds') }}
                            </td>
                            <td class="text-break">{{ $monitoringLog->response_message }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $monitoringLogs->appends(Request::except('page'))->links() }}
        </div>
    </div>
@endsection
