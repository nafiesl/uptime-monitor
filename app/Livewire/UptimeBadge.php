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
            $checkPeriodeInSeconds = $customerSite->check_periode * 60;
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
            ->get(['response_time', 'created_at'])
            ->sortKeysDesc()
            ->map(function ($monitoringLog) use ($customerSite) {
                $monitoringLog->uptime_badge_bg_color = $this->getUptimeBadgeBgColor($monitoringLog);
                $monitoringLog->uptime_badge_title = $this->getUptimeBadgeTitle($monitoringLog);

                return $monitoringLog;
            });
        return $monitoringLogs;
    }

    private function getUptimeBadgeBgColor(MonitoringLog $monitoringLog): string
    {
        if ($monitoringLog->response_time > 10000) {
            return 'danger';
        }
        if ($monitoringLog->response_time > 5000) {
            return 'warning';
        }

        return 'success';
    }

    private function getUptimeBadgeTitle(MonitoringLog $monitoringLog): string
    {
        return $monitoringLog->created_at.' ('.number_format($monitoringLog->response_time).' ms)';
    }
}
