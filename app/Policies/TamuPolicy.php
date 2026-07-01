<?php

namespace App\Policies;

use App\Models\Tamu;
use App\Models\User;

class TamuPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Tamu $tamu): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Tamu $tamu): bool
    {
        return true;
    }

    public function delete(User $user, Tamu $tamu): bool
    {
        return $user->isAdmin();
    }
}
