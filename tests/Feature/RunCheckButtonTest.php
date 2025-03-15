<?php

namespace Tests\Feature;

use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RunCheckButtonTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_run_check_now_on_a_site_detail_page()
    {
        Http::fake(['*' => Http::response([], 200)]);
        $user = $this->loginAsUser();
        $site = Site::factory()->create(['owner_id' => $user->id]);

        $this->visitRoute('sites.show', $site);
        $this->seeElement('button', ['id' => 'check_now_'.$site->id]);
        $this->press('check_now_'.$site->id);
        $this->seeRouteIs('sites.show', $site);

        $this->seeInDatabase('monitoring_logs', [
            'status_code' => 200,
            'created_at' => now()->format('Y-m-d H:i:s'),
        ]);
    }
}
