<?php

namespace App\Policies;

use App\Models\TipeKamar;
use App\Models\User;

class TipeKamarPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TipeKamar $tipeKamar): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, TipeKamar $tipeKamar): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, TipeKamar $tipeKamar): bool
    {
        return $user->isAdmin();
    }
}
