<?php

namespace App\Policies;

use App\Models\Site;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SitePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Site $site)
    {
        // Update $user authorization to view $site here.
        return true;
    }

    public function create(User $user, Site $site)
    {
        // Update $user authorization to create $site here.
        return true;
    }

    public function update(User $user, Site $site)
    {
        // Update $user authorization to update $site here.
        return true;
    }

    public function delete(User $user, Site $site)
    {
        // Update $user authorization to delete $site here.
        return true;
    }
}
