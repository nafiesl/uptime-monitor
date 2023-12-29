<span>
    <button wire:click="testTelegram()" class="btn btn-info">
        <span wire:loading>
            Sending message...
        </span>
        <span wire:loading.remove>
            Test Telegram Chat
        </span>
    </button>
    <div>{{ $message }}</div>
</span>
