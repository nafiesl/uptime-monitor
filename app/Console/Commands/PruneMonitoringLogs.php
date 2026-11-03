<?php

namespace App\Console\Commands;

use App\Models\MonitoringLog;
use App\Models\Site;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PruneMonitoringLogs extends Command
{
    protected $signature = 'monitoring-logs:prune {--chunk=1000 : Number of rows deleted per query}';

    protected $description = 'Delete monitoring logs older than the site owner\'s log retention period';

    public function handle(): void
    {
        $chunkSize = max(1, (int) $this->option('chunk'));
        $totalDeleted = 0;

        Site::with('owner')->chunkById(100, function ($sites) use ($chunkSize, &$totalDeleted) {
            foreach ($sites as $site) {
                $retentionMonths = $site->owner->log_retention_months ?? User::DEFAULT_LOG_RETENTION_MONTHS;
                $cutoffDate = Carbon::now()->subMonthsNoOverflow($retentionMonths);

                do {
                    $logIds = MonitoringLog::query()
                        ->where('site_id', $site->id)
                        ->where('created_at', '<', $cutoffDate)
                        ->limit($chunkSize)
                        ->pluck('id');
                    $deleted = $logIds->isEmpty() ? 0 : MonitoringLog::whereIn('id', $logIds)->delete();
                    $totalDeleted += $deleted;
                } while ($deleted > 0);
            }
        });

        $this->info("Done! {$totalDeleted} monitoring logs deleted.");
    }
}
