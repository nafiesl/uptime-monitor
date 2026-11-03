<?php

namespace Tests\Feature;

use App\Models\Site;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MonitoringDashboard2Test extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_search_sites_on_home2_by_name_and_vendor()
    {
        $user = $this->loginAsUser();
        $vendorA = Vendor::factory()->create(['name' => 'Vendor Alpha']);
        $vendorB = Vendor::factory()->create(['name' => 'Vendor Beta']);
        Site::factory()->create(['name' => 'Toko Online', 'vendor_id' => $vendorA->id, 'owner_id' => $user->id]);
        Site::factory()->create(['name' => 'Blog Pribadi', 'vendor_id' => $vendorB->id, 'owner_id' => $user->id]);
        Site::factory()->create(['name' => 'Toko Tanpa Vendor', 'vendor_id' => null, 'owner_id' => $user->id]);

        $this->visitRoute('home');
        $this->submitForm(__('app.search'), ['q' => 'Toko']);
        $this->see('Toko Online');
        $this->see('Toko Tanpa Vendor');
        $this->dontSee('Blog Pribadi');
        $this->see(__('vendor.without_vendor'));
        $this->dontSee('Vendor Beta</h2>');

        $this->visitRoute('home', ['vendor_id' => $vendorB->id]);
        $this->see('Blog Pribadi');
        $this->dontSee('Toko Online');

        $this->visitRoute('home', ['uptime_poll' => 1]);
        $this->see('STOP Monitoring');
        $this->see('wire:poll.60s');

        $this->visitRoute('home', ['q' => 'tidak-ada']);
        $this->see(__('site.not_found'));
    }

    /** @test */
    public function home2_shows_site_status_summary_based_on_latest_monitoring_log()
    {
        $user = $this->loginAsUser();
        $upSite = Site::factory()->create(['owner_id' => $user->id]);
        $downSite = Site::factory()->create(['owner_id' => $user->id]);
        Site::factory()->create(['owner_id' => $user->id]);
        $upSite->monitoringLogs()->create(['url' => $upSite->url, 'response_time' => 100, 'status_code' => 200]);
        $downSite->monitoringLogs()->create(['url' => $downSite->url, 'response_time' => 100, 'status_code' => 500]);

        $this->visitRoute('home');
        $this->seeElement('.status-dot.status-success');
        $this->seeElement('.status-dot.status-danger');
        $this->seeElement('.status-dot.status-secondary');
        $this->seeInElement('.tile-total .summary-count', '3');
        $this->seeInElement('.tile-up .summary-count', '1');
        $this->seeInElement('.tile-down .summary-count', '1');
        $this->seeInElement('.tile-warning .summary-count', '0');
    }
}
