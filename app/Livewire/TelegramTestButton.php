<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class TelegramTestButton extends Component
{
    public function render()
    {
        return view('livewire.telegram_test_button');
    }

    public function testTelegram(): void
    {
        Http::post('https://api.telegram.org/bot'.config('services.telegram_notifier.token').'/sendMessage', [
            'chat_id' => auth()->user()->telegram_chat_id,
            'text' => 'Uptime: Test message from '.config('app.name'),
        ]);
    }
}
