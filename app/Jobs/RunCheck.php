<?php

namespace App\Jobs;

use App\Models\CustomerSite;
use App\Models\MonitoringLog;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RunCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    private $customerSite;

    public function __construct(CustomerSite $customerSite)
    {
        $this->customerSite = $customerSite;
    }

    public function handle(): void
    {
        $force_notify = false;

        $customerSite = $this->customerSite;
        $start = microtime(true);
        try {
            if (config('queue.default') == 'sync') {
                $customerSiteTimeout = $customerSite->down_threshold / 1000;
            } else {
                $customerSiteTimeout = 30;
            }

            $response = Http::timeout($customerSiteTimeout)
                ->connectTimeout(20)
                ->get($customerSite->url);
            $statusCode = $response->status();

            if ($statusCode != 200) {
                $force_notify = true;
            }
        } catch (ConnectionException $e) {
            Log::channel('daily')->error($e);
            $statusCode = 504;
            $force_notify = true;
        } catch (RequestException $e) {
            Log::channel('daily')->error($e);
            $statusCode = 500;
            $force_notify = true;
        }
        $end = microtime(true);
        $responseTime = round(($end - $start) * 1000); // Calculate response time in milliseconds

        // Log the monitoring result to the database
        MonitoringLog::create([
            'customer_site_id' => $customerSite->id,
            'url' => $customerSite->url,
            'response_time' => $responseTime,
            'status_code' => $statusCode,
        ]);
        $customerSite->last_check_at = Carbon::now();
        $customerSite->save();

        // NOTIFY USER IF NEEDED
        if (!empty(config('services.telegram_notifier.token'))) {
            if ($force_notify && $customerSite->canNotifyUser()) {
                $responseTimes = MonitoringLog::query()
                    ->where('customer_site_id', $customerSite->id)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get(['response_time', 'status_code', 'created_at']);
                
                notifyTelegramUser($customerSite, $responseTimes, "Down");
                $customerSite->last_notify_user_at = Carbon::now();
                $customerSite->save();
            }
        }
    }
}
