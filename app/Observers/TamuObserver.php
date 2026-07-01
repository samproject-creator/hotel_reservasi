<?php

namespace App\Observers;

use App\Models\Tamu;
use App\Services\ActivityLogService;

class TamuObserver
{
    public function created(Tamu $tamu): void
    {
        ActivityLogService::logModel(
            'created', 'tamu', $tamu,
            'Data tamu baru didaftarkan: ' . $tamu->nama_lengkap . ' (NIK: ' . $tamu->nik . ')',
        );
    }

    public function updated(Tamu $tamu): void
    {
        ActivityLogService::logModel(
            'updated', 'tamu', $tamu,
            'Data tamu ' . $tamu->nama_lengkap . ' diperbarui',
        );
    }

    public function deleted(Tamu $tamu): void
    {
        ActivityLogService::logModel(
            'deleted', 'tamu', $tamu,
            'Data tamu ' . $tamu->nama_lengkap . ' dihapus',
        );
    }
}
