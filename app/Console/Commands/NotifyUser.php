<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\CustomerSite;
use App\Models\MonitoringLog;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Spatie\DiscordAlerts\Facades\DiscordAlert;

class NotifyUser extends Command
{
    protected $signature = 'notify-user';

    protected $description = 'Notify user for website down';

    public function handle(): void
    {
        // if (empty(config('services.telegram_notifier.token'))) {
        //     return;
        // }

        $customerSites = CustomerSite::where('is_active', 1)->get();

        foreach ($customerSites as $customerSite) {
            if (!$customerSite->canNotifyUser()) {
                continue;
            }
            
            $responseTimes = MonitoringLog::query()
                ->where('customer_site_id', $customerSite->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(['response_time', 'created_at','status_code']);
                
            Log::info(json_encode($responseTimes,JSON_PRETTY_PRINT));
            $responseTimeAverage = $responseTimes->avg('response_time');
            $statusCode = $responseTimes->avg('status_code');

            if ($responseTimeAverage >= ($customerSite->down_threshold * 0.9) || $statusCode >= 400) {
                $this->notifyUser($customerSite, $responseTimes, $statusCode);
                $customerSite->last_notify_user_at = Carbon::now();
                $customerSite->save();
            }
        }

        $this->info('Done!');
    }

    private function notifyUser(CustomerSite $customerSite, Collection $responseTimes, $statusCode): void
    {
        if (is_null($customerSite->owner)) {
            Log::channel('daily')->info('Missing customer site owner', $customerSite->toArray());
            return;
        }

        $telegramChatId = $customerSite->owner->telegram_chat_id;
        // if (is_null($telegramChatId)) {
        //     Log::channel('daily')->info('Missing telegram_chat_id form owner', $customerSite->toArray());
        //     return;
        // }

        $text = "";
        $text .= "Uptime: Website Down";
        $text .= "\n\n".$customerSite->name.' ('.$customerSite->url.')';
        $text .= "\n\nLast Status Code:";
        $text .= "\n". $statusCode;
        $text .= "\n\nLast 5 response time:";
        $text .= "\n";
        foreach ($responseTimes as $responseTime) {
            $text .= $responseTime->created_at->format('H:i:s').':   '.$responseTime->response_time.' ms';
            $text .= "\n";
        }
        $text .= "\nCheck here:";
        $text .= "\n".route('customer_sites.show', [$customerSite->id]);
        
        if (isset($telegramChatId)) {
            $endpoint = 'https://api.telegram.org/bot'.config('services.telegram_notifier.token').'/sendMessage';
            Http::post($endpoint, [
                'chat_id' => $telegramChatId,
                'text' => $text,
            ]);
        }


        $discordWebhook = $customerSite->webhook_url;

        // if (is_null($discordWebhook)) {
        //     Log::channel('daily')->info('Missing webhook_url form owner', $customerSite->toArray());
        //     return;
        // }

        if (isset($discordWebhook)) {
            DiscordAlert::to($discordWebhook)
            ->message("Issues : {$customerSite->name} {$customerSite->url} !",
            [[
                'title' => 'Uptime: Website Down',
                'description' => $text,
                'color' => '#E77625',
                'author' => [
                    'name' => 'Spatie',
                    'url' => 'https://spatie.be/'
                ]    
            ]]);
        }
    }
}
