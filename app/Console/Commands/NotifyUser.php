<?php

namespace App\Console\Commands;

use App\Models\CustomerSite;
use App\Models\MonitoringLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotifyUser extends Command
{
    protected $signature = 'notify-user';

    protected $description = 'Notify user for website down';

    public function handle(): void
    {
        if (empty(config('services.telegram_notifier.token'))) {
            return;
        }

        $customerSites = CustomerSite::where('is_active', 1)->get();

        foreach ($customerSites as $customerSite) {
            if (!$customerSite->canNotifyUser()) {
                continue;
            }
            $responseTimes = MonitoringLog::query()
                ->where('customer_site_id', $customerSite->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(['response_time', 'created_at']);
            $responseTimeAverage = $responseTimes->avg('response_time');
            if ($responseTimeAverage >= ($customerSite->down_threshold * 0.9)) {
                notifyTelegramUser($customerSite, $responseTimes);
                $customerSite->last_notify_user_at = Carbon::now();
                $customerSite->save();
            }
        }

        $this->info('Done!');
    }
}
