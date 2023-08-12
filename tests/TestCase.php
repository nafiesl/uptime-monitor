<?php

namespace Tests;

use App\Models\User;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public $baseUrl = 'http://localhost';

    protected function loginAsUser($userDataOverrides = [])
    {
        $user = $this->createUser($userDataOverrides);
        $this->actingAs($user);

        return $user;
    }

    protected function createUser($userDataOverrides = [])
    {
        return User::factory()->create($userDataOverrides);
    }
}
