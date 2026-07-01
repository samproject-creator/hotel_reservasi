<?php

namespace App\Observers;

use App\Models\Kamar;
use App\Services\ActivityLogService;

class KamarObserver
{
    public function created(Kamar $kamar): void
    {
        ActivityLogService::logModel(
            'created', 'kamar', $kamar,
            'Kamar baru ditambahkan: No. ' . $kamar->nomor_kamar . ' (Lantai ' . $kamar->lantai . ')',
        );
    }

    public function updated(Kamar $kamar): void
    {
        $desc = $kamar->isDirty('status')
            ? 'Status kamar ' . $kamar->nomor_kamar . ' diubah menjadi "' . $kamar->status . '"'
            : 'Data kamar ' . $kamar->nomor_kamar . ' diperbarui';

        ActivityLogService::logModel('updated', 'kamar', $kamar, $desc);
    }

    public function deleted(Kamar $kamar): void
    {
        ActivityLogService::logModel(
            'deleted', 'kamar', $kamar,
            'Kamar No. ' . $kamar->nomor_kamar . ' dihapus',
        );
    }
}
