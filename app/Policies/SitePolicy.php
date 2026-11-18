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
        return $user->id === $site->owner_id;
    }

    public function create(User $user, Site $site)
    {
        return true;
    }

    public function update(User $user, Site $site)
    {
        return $user->id === $site->owner_id;
    }

    public function delete(User $user, Site $site)
    {
        return $user->id === $site->owner_id;
    }
}
