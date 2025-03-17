<?php

namespace App\Console\Commands;

use App\Models\MonitoringLog;
use App\Models\Site;
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

        $sites = Site::where('is_active', 1)->get();

        foreach ($sites as $site) {
            if (!$site->canNotifyUser()) {
                continue;
            }
            $responseTimes = MonitoringLog::query()
                ->where('site_id', $site->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(['response_time', 'status_code', 'created_at']);
            $responseTimeAverage = $responseTimes->avg('response_time');
            if ($responseTimes->avg('response_time') >= ($site->down_threshold * 0.9) || $responseTimes->avg('status_code') >= 400) {
                $this->notifyUser($site, $responseTimes);
                $site->last_notify_user_at = Carbon::now();
                $site->save();
            }
        }

        $this->info('Done!');
    }

    private function notifyUser(Site $site, Collection $responseTimes): void
    {
        if (is_null($site->owner)) {
            Log::channel('daily')->info('Missing customer site owner', $site->toArray());
            return;
        }

        $telegramChatId = $site->owner->telegram_chat_id;
        if (is_null($telegramChatId)) {
            Log::channel('daily')->info('Missing telegram_chat_id form owner', $site->toArray());
            return;
        }

        $endpoint = 'https://api.telegram.org/bot'.config('services.telegram_notifier.token').'/sendMessage';
        $text = "";
        $text .= "Uptime: Website Down";
        $text .= "\n\n".$site->name.' ('.$site->url.')';
        $text .= "\n\nLast 5 response time:";
        $text .= "\n";
        foreach ($responseTimes as $responseTime) {
            $text .= $responseTime->created_at->format('H:i:s').':   '.$responseTime->response_time.' ms';
            $text .= '   (status code: '.$responseTime->status_code.')';
            $text .= "\n";
        }
        $text .= "\nCheck here:";
        $text .= "\n".route('sites.show', [$site->id]);
        Http::post($endpoint, [
            'chat_id' => $telegramChatId,
            'text' => $text,
        ]);
    }
}
