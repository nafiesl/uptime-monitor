<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\CustomerSite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerSiteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_customer_site_has_title_link_attribute()
    {
        $customerSite = CustomerSite::factory()->create();

        $this->assertEquals(
            link_to_route('customer_sites.show', $customerSite->title, [$customerSite], [
                'title' => __(
                    'app.show_detail_title',
                    ['title' => $customerSite->title, 'type' => __('customer_site.customer_site')]
                ),
            ]), $customerSite->title_link
        );
    }

    /** @test */
    public function a_customer_site_has_belongs_to_creator_relation()
    {
        $customerSite = CustomerSite::factory()->make();

        $this->assertInstanceOf(User::class, $customerSite->creator);
        $this->assertEquals($customerSite->creator_id, $customerSite->creator->id);
    }
}
