<?php

namespace App\Policies;

use App\Models\Kamar;
use App\Models\User;

class KamarPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Kamar $kamar): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Kamar $kamar): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Kamar $kamar): bool
    {
        return $user->isAdmin();
    }
}
