<?php

namespace App\Livewire;

use Livewire\Component;

class UptimeBadge extends Component
{
    public $uptimePoll = 0;
    public $uptimePollState = null;
    public $customerSite;

    public function mount()
    {
        if ($this->uptimePoll == 1) {
            $checkPeriodeInSeconds = $this->customerSite->check_periode * 60;
            $this->uptimePollState = 'wire:poll.'.($checkPeriodeInSeconds).'s';
        }
    }

    public function render()
    {
        return view('livewire.uptime_badge');
    }
}
