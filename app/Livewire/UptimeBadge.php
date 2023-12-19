<?php

namespace App\Livewire;

use App\Models\CustomerSite;
use App\Models\MonitoringLog;
use Livewire\Component;

class UptimeBadge extends Component
{
    public $uptimePoll = 0;
    public $uptimePollState = null;
    public $customerSite;
    public $monitoringLogs;

    public function mount()
    {
        $customerSite = $this->customerSite;
        if ($this->uptimePoll == 1) {
            $checkPeriodeInSeconds = $customerSite->check_interval * 60;
            $this->uptimePollState = 'wire:poll.'.($checkPeriodeInSeconds).'s';
        }
    }

    public function render()
    {
        return view('livewire.uptime_badge');
    }

    private function getCustomerSiteMonitoringLogs(CustomerSite $customerSite)
    {
        $monitoringLogs = $customerSite->monitoringLogs()
            ->latest('id')
            ->take(15)
            ->get(['response_time', 'status_code', 'created_at'])
            ->sortKeysDesc()
            ->map(function ($monitoringLog) use ($customerSite) {
                $monitoringLog->uptime_badge_bg_color = $this->getUptimeBadgeBgColor($customerSite, $monitoringLog);
                $monitoringLog->uptime_badge_title = $this->getUptimeBadgeTitle($monitoringLog);

                return $monitoringLog;
            });
        return $monitoringLogs;
    }

    private function getUptimeBadgeBgColor(CustomerSite $customerSite, MonitoringLog $monitoringLog): string
    {
        if ($monitoringLog->status_code >= 500) {
            return 'danger';
        }
        if ($monitoringLog->status_code >= 400) {
            return 'danger';
        }
        if ($monitoringLog->response_time >= $customerSite->down_threshold) {
            return 'danger';
        }
        if ($monitoringLog->status_code >= 300) {
            return 'warning';
        }
        if ($monitoringLog->response_time >= $customerSite->warning_threshold) {
            return 'warning';
        }

        return 'success';
    }

    private function getUptimeBadgeTitle(MonitoringLog $monitoringLog): string
    {
        return $monitoringLog->created_at.' (time:'.number_format($monitoringLog->response_time).'ms code:'.$monitoringLog->status_code.')';
    }
}
