<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class TelegramTestButton extends Component
{
    public $message = '';

    public function render()
    {
        return view('livewire.telegram_test_button');
    }

    public function testTelegram(): void
    {
        $endpoint = 'https://api.telegram.org/bot'.config('services.telegram_notifier.token').'/sendMessage';
        $response = Http::post($endpoint, [
            'chat_id' => auth()->user()->telegram_chat_id,
            'text' => 'Uptime: Test message from '.config('app.name'),
        ]);
        Log::channel('daily')->info($response->body());

        $responseBody = json_decode($response->body());
        if ($responseBody->ok == false) {
            $this->message = $response->body();
        } else {
            $this->message = '';
        }
    }
}
