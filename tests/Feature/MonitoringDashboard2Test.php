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

        $this->visitRoute('home', ['q' => 'tidak-ada']);
        $this->see(__('site.not_found'));
    }
}
