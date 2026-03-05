<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Bundle;
use Illuminate\Auth\Access\Response;

class BundlePolicy
{
    public function viewAny(User $user): bool {
        return false;
    }

    public function view(User $user, Bundle $bundle): bool {
        return false;
    }

    public function create(User $user): bool {
        return false;
    }

    public function update(User $user, Bundle $bundle): bool {
        return false;
    }

    public function delete(User $user, Bundle $bundle): bool {
        return false;
    }

    public function restore(User $user, Bundle $bundle): bool {
        return false;
    }

    public function forceDelete(User $user, Bundle $bundle): bool {
        return false;
    }
}
