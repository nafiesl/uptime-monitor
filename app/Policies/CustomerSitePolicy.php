<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CustomerSite;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerSitePolicy
{
    use HandlesAuthorization;

    public function view(User $user, CustomerSite $customerSite)
    {
        // Update $user authorization to view $customerSite here.
        return true;
    }

    public function create(User $user, CustomerSite $customerSite)
    {
        // Update $user authorization to create $customerSite here.
        return true;
    }

    public function update(User $user, CustomerSite $customerSite)
    {
        // Update $user authorization to update $customerSite here.
        return true;
    }

    public function delete(User $user, CustomerSite $customerSite)
    {
        // Update $user authorization to delete $customerSite here.
        return true;
    }
}
