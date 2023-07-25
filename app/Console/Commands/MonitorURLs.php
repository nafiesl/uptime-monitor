<?php

namespace App\Console\Commands;

use App\Models\MonitoringLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class MonitorURLs extends Command
{
    protected $signature = 'monitor:urls';

    protected $description = 'Monitor the given URLs';

    public function handle()
    {
        $urls = ['https://google.com', 'https://yahoo.com']; // Add your desired URLs here

        foreach ($urls as $url) {
            $start = microtime(true);
            $response = Http::get($url);
            $end = microtime(true);
            $responseTime = round(($end - $start) * 1000); // Calculate response time in milliseconds

            $statusCode = $response->status();

            // Log the monitoring result to the database
            MonitoringLog::create([
                'url' => $url,
                'response_time' => $responseTime,
                'status_code' => $statusCode,
            ]);
        }

        $this->info('URLs monitored successfully.');
    }
}
