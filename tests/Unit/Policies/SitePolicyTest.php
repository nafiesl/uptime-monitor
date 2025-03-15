<?php

namespace Tests\Unit\Policies;

use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitePolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_site()
    {
        $user = $this->createUser();
        $this->assertTrue($user->can('create', new Site));
    }

    /** @test */
    public function user_can_view_site()
    {
        $user = $this->createUser();
        $site = Site::factory()->create();
        $this->assertTrue($user->can('view', $site));
    }

    /** @test */
    public function user_can_update_site()
    {
        $user = $this->createUser();
        $site = Site::factory()->create();
        $this->assertTrue($user->can('update', $site));
    }

    /** @test */
    public function user_can_delete_site()
    {
        $user = $this->createUser();
        $site = Site::factory()->create();
        $this->assertTrue($user->can('delete', $site));
    }
}
