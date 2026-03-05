<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;

class RolePolicy {
    public function viewAny(User $user) {
        return $user->isAdmin();
    }

    public function view(User $user, Role $model) {
        return $user->id === $model->id || $user->isAdmin();
    }

    public function create(?User $user) {
        return true;
    }

    public function update(User $user, User $model) {
        return $user->id === $model->id || $user->role->id === 1;
    }

    public function delete(User $user) {
        return $user->role->id === 1;
    }
}
