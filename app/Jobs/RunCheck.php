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
        $customerSite = $this->customerSite;
        $start = microtime(true);
        try {
            $customerSiteTimeout = $customerSite->down_threshold / 1000;
            $response = Http::timeout($customerSiteTimeout)
                ->connectTimeout(20)
                ->get($customerSite->url);
            $statusCode = $response->status();
        } catch (ConnectionException $e) {
            Log::channel('daily')->error($e);
            $statusCode = 500;
        } catch (RequestException $e) {
            Log::channel('daily')->error($e);
            $statusCode = 500;
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
    }
}
