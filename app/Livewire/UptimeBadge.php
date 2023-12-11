<?php

namespace App\Livewire;

use Livewire\Component;

class UptimeBadge extends Component
{
    public $customerSite;

    public function render()
    {
        return view('livewire.uptime_badge');
    }
}
