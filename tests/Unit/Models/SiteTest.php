<?php

namespace Tests\Unit\Models;

use App\Models\Site;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function site_model_has_belongs_to_owner_relation()
    {
        $site = Site::factory()->make();

        $this->assertInstanceOf(User::class, $site->owner);
        $this->assertEquals($site->owner_id, $site->owner->id);
    }

    /** @test */
    public function site_model_has_need_to_check_method()
    {
        $site = Site::factory()->make([
            'is_active' => 0,
            'last_check_at' => null,
        ]);
        $this->assertFalse($site->needToCheck());

        $site->is_active = 1;
        $site->last_check_at = null;
        $this->assertTrue($site->needToCheck());

        $site->check_interval = 2;
        $site->last_check_at = '2023-12-11 00:01:16';
        Carbon::setTestNow('2023-12-11 00:02:17');
        $this->assertTrue($site->needToCheck());

        $site->check_interval = 5;
        $site->last_check_at = '2023-12-11 00:00:00';
        Carbon::setTestNow('2023-12-11 00:03:00');
        $this->assertFalse($site->needToCheck());

        $site->check_interval = 5;
        $site->last_check_at = '2023-12-11 00:00:00';
        Carbon::setTestNow('2023-12-11 00:05:00');
        $this->assertTrue($site->needToCheck());

        $site->check_interval = 5;
        $site->last_check_at = '2023-12-11 00:00:00';
        Carbon::setTestNow('2023-12-11 00:04:00');
        $this->assertTrue($site->needToCheck());

        Carbon::setTestNow();
    }

    /** @test */
    public function site_model_has_can_notify_user_method()
    {
        $site = Site::factory()->make([
            'is_active' => 0,
            'last_notify_user_at' => null,
        ]);
        $this->assertFalse($site->canNotifyUser());

        $site->is_active = 1;
        $site->last_notify_user_at = null;
        $this->assertTrue($site->canNotifyUser());

        $site->notify_user_interval = 2;
        $site->last_notify_user_at = '2023-12-11 00:01:16';
        Carbon::setTestNow('2023-12-11 00:02:17');
        $this->assertTrue($site->canNotifyUser());

        $site->notify_user_interval = 5;
        $site->last_notify_user_at = '2023-12-11 00:00:00';
        Carbon::setTestNow('2023-12-11 00:03:00');
        $this->assertFalse($site->canNotifyUser());

        $site->notify_user_interval = 5;
        $site->last_notify_user_at = '2023-12-11 00:00:00';
        Carbon::setTestNow('2023-12-11 00:05:00');
        $this->assertTrue($site->canNotifyUser());

        $site->notify_user_interval = 5;
        $site->last_notify_user_at = '2023-12-11 00:00:00';
        Carbon::setTestNow('2023-12-11 00:04:00');
        $this->assertTrue($site->canNotifyUser());

        Carbon::setTestNow();
    }

    /** @test */
    public function site_model_has_max_y_axis_attribute()
    {
        $site = Site::factory()->make(['down_threshold' => 10000]);

        $this->assertEquals(12000, $site->y_axis_max);
    }

    /** @test */
    public function site_model_has_y_axis_tick_amount_attribute()
    {
        $site = Site::factory()->make(['down_threshold' => 10000]);

        $this->assertEquals(12, $site->y_axis_tick_amount);
    }

    /** @test */
    public function site_model_has_belongs_to_vendor_relation()
    {
        $vendor = Vendor::factory()->create();
        $site = Site::factory()->create(['vendor_id' => $vendor->id]);

        $this->assertInstanceOf(Vendor::class, $site->vendor);
        $this->assertEquals($site->vendor_id, $site->vendor->id);
    }
}
