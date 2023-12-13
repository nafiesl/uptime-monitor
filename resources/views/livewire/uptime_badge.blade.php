<div {{ $uptimePollState }}>
    @foreach ($this->getCustomerSiteMonitoringLogs($customerSite) as $monitoringLog)
        <span
            class="badge bg-{{ $monitoringLog->uptime_badge_bg_color }} log_indicator"
            title="{{ $monitoringLog->uptime_badge_title }}">&nbsp;</span>
    @endforeach
</div>
