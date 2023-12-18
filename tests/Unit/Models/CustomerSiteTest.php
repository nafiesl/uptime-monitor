<?php

namespace Tests\Unit\Models;

use App\Models\CustomerSite;
use App\Models\User;
use App\Models\Vendor;
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

        $customerSite->check_interval = 2;
        $customerSite->last_check_at = '2023-12-11 00:01:16';
        Carbon::setTestNow('2023-12-11 00:02:17');
        $this->assertTrue($customerSite->needToCheck());

        $customerSite->check_interval = 5;
        $customerSite->last_check_at = '2023-12-11 00:00:00';
        Carbon::setTestNow('2023-12-11 00:03:00');
        $this->assertFalse($customerSite->needToCheck());

        $customerSite->check_interval = 5;
        $customerSite->last_check_at = '2023-12-11 00:00:00';
        Carbon::setTestNow('2023-12-11 00:05:00');
        $this->assertTrue($customerSite->needToCheck());

        $customerSite->check_interval = 5;
        $customerSite->last_check_at = '2023-12-11 00:00:00';
        Carbon::setTestNow('2023-12-11 00:04:00');
        $this->assertTrue($customerSite->needToCheck());

        Carbon::setTestNow();
    }

    /** @test */
    public function customer_site_model_has_can_notify_user_method()
    {
        $customerSite = CustomerSite::factory()->make([
            'is_active' => 0,
            'last_notify_user_at' => null,
        ]);
        $this->assertFalse($customerSite->canNotifyUser());

        $customerSite->is_active = 1;
        $customerSite->last_notify_user_at = null;
        $this->assertTrue($customerSite->canNotifyUser());

        $customerSite->notify_user_interval = 2;
        $customerSite->last_notify_user_at = '2023-12-11 00:01:16';
        Carbon::setTestNow('2023-12-11 00:02:17');
        $this->assertTrue($customerSite->canNotifyUser());

        $customerSite->notify_user_interval = 5;
        $customerSite->last_notify_user_at = '2023-12-11 00:00:00';
        Carbon::setTestNow('2023-12-11 00:03:00');
        $this->assertFalse($customerSite->canNotifyUser());

        $customerSite->notify_user_interval = 5;
        $customerSite->last_notify_user_at = '2023-12-11 00:00:00';
        Carbon::setTestNow('2023-12-11 00:05:00');
        $this->assertTrue($customerSite->canNotifyUser());

        $customerSite->notify_user_interval = 5;
        $customerSite->last_notify_user_at = '2023-12-11 00:00:00';
        Carbon::setTestNow('2023-12-11 00:04:00');
        $this->assertTrue($customerSite->canNotifyUser());

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

    /** @test */
    public function customer_site_model_has_belongs_to_vendor_relation()
    {
        $vendor = Vendor::factory()->create();
        $customerSite = CustomerSite::factory()->create(['vendor_id' => $vendor->id]);

        $this->assertInstanceOf(Vendor::class, $customerSite->vendor);
        $this->assertEquals($customerSite->vendor_id, $customerSite->vendor->id);
    }
}
