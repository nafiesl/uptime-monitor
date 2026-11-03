<?php

namespace App\Livewire;

use App\Models\MonitoringLog;
use App\Models\Site;
use Livewire\Component;

class UptimeBadge extends Component
{
    public $uptimePoll = 0;
    public $uptimePollState = null;
    public $site;
    public $monitoringLogs;

    public function mount()
    {
        $site = $this->site;
        if ($this->uptimePoll == 1) {
            $checkPeriodeInSeconds = $site->check_interval * 60;
            $this->uptimePollState = 'wire:poll.'.($checkPeriodeInSeconds).'s';
        }
    }

    public function render()
    {
        return view('livewire.uptime_badge');
    }

    private function getSiteMonitoringLogs(Site $site)
    {
        $monitoringLogs = $site->monitoringLogs()
            ->latest('id')
            ->take(15)
            ->get(['response_time', 'status_code', 'created_at'])
            ->sortKeysDesc()
            ->map(function ($monitoringLog) use ($site) {
                $monitoringLog->uptime_badge_bg_color = $site->getStatusColor($monitoringLog);
                $monitoringLog->uptime_badge_title = $this->getUptimeBadgeTitle($monitoringLog);

                return $monitoringLog;
            });
        return $monitoringLogs;
    }

    private function getUptimeBadgeTitle(MonitoringLog $monitoringLog): string
    {
        return $monitoringLog->created_at.' (time:'.number_format($monitoringLog->response_time).'ms code:'.$monitoringLog->status_code.')';
    }
}
