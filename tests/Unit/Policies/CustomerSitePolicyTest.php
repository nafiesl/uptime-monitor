<?php

namespace Tests\Unit\Policies;

use App\Models\CustomerSite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerSitePolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_customer_site()
    {
        $user = $this->createUser();
        $this->assertTrue($user->can('create', new CustomerSite));
    }

    /** @test */
    public function user_can_view_customer_site()
    {
        $user = $this->createUser();
        $customerSite = CustomerSite::factory()->create();
        $this->assertTrue($user->can('view', $customerSite));
    }

    /** @test */
    public function user_can_update_customer_site()
    {
        $user = $this->createUser();
        $customerSite = CustomerSite::factory()->create();
        $this->assertTrue($user->can('update', $customerSite));
    }

    /** @test */
    public function user_can_delete_customer_site()
    {
        $user = $this->createUser();
        $customerSite = CustomerSite::factory()->create();
        $this->assertTrue($user->can('delete', $customerSite));
    }
}
