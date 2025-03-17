<?php

namespace App\Console\Commands;

use App\Jobs\RunCheck;
use App\Models\Site;
use Illuminate\Console\Command;

class MonitorURLs extends Command
{
    protected $signature = 'monitor:urls';

    protected $description = 'Monitor the given URLs';

    public function handle()
    {
        $sites = Site::where('is_active', 1)->get(); // Add your desired URLs here

        foreach ($sites as $site) {
            if (!$site->needToCheck()) {
                continue;
            }

            RunCheck::dispatch($site);
        }

        $this->info('URLs monitored successfully.');
    }
}
