<?php

namespace App\Jobs;

use App\Models\MonitoringLog;
use App\Models\Site;
use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
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

    private $site;

    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    public function handle(): void
    {
        $site = $this->site;
        $start = microtime(true);
        $responseMessage = null;
        try {
            $siteTimeout = $site->down_threshold / 1000;
            $response = Http::timeout($siteTimeout)
                ->connectTimeout(20)
                ->get($site->url);
            $statusCode = $response->status();
        } catch (ConnectionException $e) {
            Log::channel('daily')->error($e);
            $responseMessage = $e->getMessage();
            $statusCode = 500;
        } catch (RequestException $e) {
            Log::channel('daily')->error($e);
            $responseMessage = $e->getMessage();
            $statusCode = 500;
        } catch (GuzzleRequestException $e) {
            Log::channel('daily')->error($e);
            $responseMessage = $e->getMessage();
            $statusCode = 500;
        }
        $end = microtime(true);
        $responseTime = round(($end - $start) * 1000); // Calculate response time in milliseconds

        // Log the monitoring result to the database
        MonitoringLog::create([
            'site_id' => $site->id,
            'url' => $site->url,
            'response_time' => $responseTime,
            'status_code' => $statusCode,
            'response_message' => $responseMessage,
        ]);
        $site->last_check_at = Carbon::now();
        $site->save();
    }
}
