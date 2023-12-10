<?php

namespace Tests\Unit\Models;

use App\Models\CustomerSite;
use App\Models\User;
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
}
