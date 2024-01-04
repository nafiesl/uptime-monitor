<?php

namespace App\Console\Commands;

use App\Jobs\RunCheck;
use App\Models\CustomerSite;
use Illuminate\Console\Command;

class MonitorURLs extends Command
{
    protected $signature = 'monitor:urls';

    protected $description = 'Monitor the given URLs';

    public function handle()
    {
        $customerSites = CustomerSite::where('is_active', 1)->get(); // Add your desired URLs here

        foreach ($customerSites as $customerSite) {
            if (!$customerSite->needToCheck()) {
                continue;
            }

            RunCheck::dispatch($customerSite);
        }

        $this->info('URLs monitored successfully.');
    }
}
