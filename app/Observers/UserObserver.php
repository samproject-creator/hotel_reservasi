<?php

namespace App\Observers;

use App\Models\User;
use App\Services\ActivityLogService;

class UserObserver
{
    public function created(User $user): void
    {
        ActivityLogService::logModel(
            'created', 'user', $user,
            'Akun user baru dibuat: ' . $user->name . ' (' . $user->role . ')',
        );
    }

    public function updated(User $user): void
    {
        $desc = $user->isDirty('is_active')
            ? 'Status akun ' . $user->name . ' diubah menjadi ' . ($user->is_active ? 'Aktif' : 'Nonaktif')
            : 'Data user ' . $user->name . ' diperbarui';

        ActivityLogService::logModel('updated', 'user', $user, $desc);
    }

    public function deleted(User $user): void
    {
        ActivityLogService::logModel(
            'deleted', 'user', $user,
            'Akun user ' . $user->name . ' dihapus',
        );
    }
}
