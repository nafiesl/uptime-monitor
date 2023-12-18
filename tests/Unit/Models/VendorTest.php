<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_vendor_has_title_link_attribute()
    {
        $vendor = Vendor::factory()->create();

        $this->assertEquals(
            link_to_route('vendors.show', $vendor->name, [$vendor], [
                'title' => __(
                    'app.show_detail_title',
                    ['title' => $vendor->name, 'type' => __('vendor.vendor')]
                ),
            ]), $vendor->title_link
        );
    }

    /** @test */
    public function a_vendor_has_belongs_to_creator_relation()
    {
        $vendor = Vendor::factory()->make();

        $this->assertInstanceOf(User::class, $vendor->creator);
        $this->assertEquals($vendor->creator_id, $vendor->creator->id);
    }
}
