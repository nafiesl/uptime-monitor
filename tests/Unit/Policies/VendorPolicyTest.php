<?php

namespace Tests\Unit\Policies;

use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_vendor()
    {
        $user = $this->createUser();
        $this->assertTrue($user->can('create', new Vendor));
    }

    /** @test */
    public function user_can_view_vendor()
    {
        $user = $this->createUser();
        $vendor = Vendor::factory()->create();
        $this->assertTrue($user->can('view', $vendor));
    }

    /** @test */
    public function user_can_update_vendor()
    {
        $user = $this->createUser();
        $vendor = Vendor::factory()->create();
        $this->assertTrue($user->can('update', $vendor));
    }

    /** @test */
    public function user_can_delete_vendor()
    {
        $user = $this->createUser();
        $vendor = Vendor::factory()->create();
        $this->assertTrue($user->can('delete', $vendor));
    }
}
