<?php

use App\Models\CustomerSite;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


if (!function_exists('notifyTelegramUser')) {   
    function notifyTelegramUser(CustomerSite $customerSite, Collection $responseTimes, $status = "Slow"): void {
        if (is_null($customerSite->owner)) {
            Log::channel('daily')->info('Missing customer site owner', $customerSite->toArray());
            return;
        }

        $telegramChatId = $customerSite->owner->telegram_chat_id;
        if (is_null($telegramChatId)) {
            Log::channel('daily')->info('Missing telegram_chat_id form owner', $customerSite->toArray());
            return;
        }

        $endpoint = 'https://api.telegram.org/bot'.config('services.telegram_notifier.token').'/sendMessage';
        $text = "";
        $text .= "Uptime: Website $status";
        $text .= "\n\n".$customerSite->name.' ('.$customerSite->url.')';
        $text .= "\n\nLast 5 response time:";
        $text .= "\n";
        foreach ($responseTimes as $responseTime) {
            $text .= "Code : $responseTime->status_code | " . $responseTime->created_at->format('H:i:s').':   '.$responseTime->response_time.' ms';
            $text .= "\n";
        }
        $text .= "\nCheck here:";

        if ($customerSite->visibility == "public") {
            $text .= "\n".route('customer_sites.public-show', [$customerSite->id]);
        } else {
            $text .= "\n".route('customer_sites.show', [$customerSite->id]);
        }

        Http::post($endpoint, [
            'chat_id' => $telegramChatId,
            'text' => $text,
        ]);
    }
}