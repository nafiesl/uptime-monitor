<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Vendor $vendor)
    {
        // Update $user authorization to view $vendor here.
        return true;
    }

    public function create(User $user, Vendor $vendor)
    {
        // Update $user authorization to create $vendor here.
        return true;
    }

    public function update(User $user, Vendor $vendor)
    {
        // Update $user authorization to update $vendor here.
        return true;
    }

    public function delete(User $user, Vendor $vendor)
    {
        // Update $user authorization to delete $vendor here.
        return true;
    }
}
