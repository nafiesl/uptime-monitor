<?php

namespace Tests\Feature;

use App\Models\CustomerSite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RunCheckButtonTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_run_check_now_on_a_customer_site_detail_page()
    {
        Http::fake(['*' => Http::response([], 200)]);
        $user = $this->loginAsUser();
        $customerSite = CustomerSite::factory()->create(['owner_id' => $user->id]);

        $this->visitRoute('customer_sites.show', $customerSite);
        $this->seeElement('button', ['id' => 'check_now_'.$customerSite->id]);
        $this->press('check_now_'.$customerSite->id);
        $this->seeRouteIs('customer_sites.show', $customerSite);

        $this->seeInDatabase('monitoring_logs', [
            'status_code' => 200,
            'created_at' => now()->format('Y-m-d H:i:s'),
        ]);
    }
}
