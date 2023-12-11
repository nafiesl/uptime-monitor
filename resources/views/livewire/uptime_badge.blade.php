<div {{ $uptimePollState }}>
    @php
        $monitoringLogs = $customerSite->monitoringLogs()
            ->latest('id')
            ->take(15)
            ->get(['response_time', 'created_at'])
            ->sortKeysDesc();
    @endphp
    @foreach ($monitoringLogs as $monitoringLog)
        @php
            $bgColor = 'success';
            if ($monitoringLog->response_time > 5000) {
                $bgColor = 'warning';
            }
            if ($monitoringLog->response_time > 10000) {
                $bgColor = 'danger';
            }
        @endphp
        <span class="badge bg-{{ $bgColor }} log_indicator" title="{{ $monitoringLog->created_at }} ({{ number_format($monitoringLog->response_time) }} ms)">&nbsp;</span>
    @endforeach
</div>
