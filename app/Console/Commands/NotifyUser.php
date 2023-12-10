<?php

namespace App\Console\Commands;

use App\Models\CustomerSite;
use App\Models\MonitoringLog;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class NotifyUser extends Command
{
    protected $signature = 'notify-user';

    protected $description = 'Notify user for website down';

    public function handle(): void
    {
        if (empty(config('services.telegram_notifier.token')) || empty(config('services.telegram_notifier.chat_id'))) {
            return;
        }

        $customerSites = CustomerSite::where('is_active', 1)->get();

        foreach ($customerSites as $customerSite) {
            $responseTimes = MonitoringLog::query()
                ->where('customer_site_id', $customerSite->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(['response_time', 'created_at']);
            $responseTimeAverage = $responseTimes->avg('response_time');
            if ($responseTimes->avg('response_time') >= 9000) {
                $this->notifyUser($customerSite, $responseTimes);
            }
        }

        $this->info('Done!');
    }

    private function notifyUser(CustomerSite $customerSite, Collection $responseTimes): void
    {
        $endpoint = 'https://api.telegram.org/bot'.config('services.telegram_notifier.token').'/sendMessage';
        $text = "";
        $text .= "Uptime: Website Down";
        $text .= "\n\n".$customerSite->name.' ('.$customerSite->url.')';
        $text .= "\n\nResponse 5 menit terakhir:";
        $text .= "\n";
        foreach ($responseTimes as $responseTime) {
            $text .= $responseTime->created_at->format('H:i:s').':   '.$responseTime->response_time.' ms';
            $text .= "\n";
        }
        $text .= "\nCek di sini:";
        $text .= "\n".route('customer_sites.show', [$customerSite->id, 'time_range' => '6h']);
        Http::post($endpoint, [
            'chat_id' => config('services.telegram_notifier.chat_id'),
            'text' => $text,
        ]);
    }
}
