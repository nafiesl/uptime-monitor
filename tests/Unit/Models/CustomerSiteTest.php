<?php

namespace Tests\Unit\Models;

use App\Models\CustomerSite;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerSiteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function customer_site_model_has_belongs_to_owner_relation()
    {
        $customerSite = CustomerSite::factory()->make();

        $this->assertInstanceOf(User::class, $customerSite->owner);
        $this->assertEquals($customerSite->owner_id, $customerSite->owner->id);
    }

    /** @test */
    public function customer_site_model_has_need_to_check_method()
    {
        $customerSite = CustomerSite::factory()->make([
            'is_active' => 0,
            'last_check_at' => null,
        ]);
        $this->assertFalse($customerSite->needToCheck());

        $customerSite->is_active = 1;
        $customerSite->last_check_at = null;
        $this->assertTrue($customerSite->needToCheck());

        $customerSite->check_periode = 2;
        $customerSite->last_check_at = '2023-12-11 00:01:16';
        Carbon::setTestNow('2023-12-11 00:02:17');
        $this->assertTrue($customerSite->needToCheck());

        $customerSite->check_periode = 5;
        $customerSite->last_check_at = '2023-12-11 00:00:00';
        Carbon::setTestNow('2023-12-11 00:03:00');
        $this->assertFalse($customerSite->needToCheck());

        $customerSite->check_periode = 5;
        $customerSite->last_check_at = '2023-12-11 00:00:00';
        Carbon::setTestNow('2023-12-11 00:05:00');
        $this->assertTrue($customerSite->needToCheck());

        $customerSite->check_periode = 5;
        $customerSite->last_check_at = '2023-12-11 00:00:00';
        Carbon::setTestNow('2023-12-11 00:04:00');
        $this->assertTrue($customerSite->needToCheck());

        Carbon::setTestNow();
    }

    /** @test */
    public function customer_site_model_has_max_y_axis_attribute()
    {
        $customerSite = CustomerSite::factory()->make(['down_threshold' => 10000]);

        $this->assertEquals(12000, $customerSite->y_axis_max);
    }

    /** @test */
    public function customer_site_model_has_y_axis_tick_amount_attribute()
    {
        $customerSite = CustomerSite::factory()->make(['down_threshold' => 10000]);

        $this->assertEquals(12, $customerSite->y_axis_tick_amount);
    }
}
