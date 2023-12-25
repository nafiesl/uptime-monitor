<?php

namespace App\Console\Commands;

use App\Models\CustomerSite;
use App\Models\MonitoringLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class MonitorURLs extends Command
{
    protected $signature = 'monitor:urls';

    protected $description = 'Monitor the given URLs';

    public function handle()
    {
        $customerSites = CustomerSite::where('is_active', 1)->get(); // Add your desired URLs here

        foreach ($customerSites as $customerSite) {
            if (!$customerSite->needToCheck()) {
                continue;
            }

            $start = microtime(true);
            try {
                $response = Http::timeout($customerSite->down_threshold / 1000)->get($customerSite->url);
                $statusCode = $response->status();
            } catch (ConnectionException $e) {
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

        $this->info('URLs monitored successfully.');
    }
}
