<?php

namespace App\Observers;

use App\Models\TipeKamar;
use App\Services\ActivityLogService;

class TipeKamarObserver
{
    public function created(TipeKamar $tipeKamar): void
    {
        ActivityLogService::logModel(
            'created', 'tipe_kamar', $tipeKamar,
            'Tipe kamar baru ditambahkan: ' . $tipeKamar->nama_tipe,
        );
    }

    public function updated(TipeKamar $tipeKamar): void
    {
        ActivityLogService::logModel(
            'updated', 'tipe_kamar', $tipeKamar,
            'Data tipe kamar ' . $tipeKamar->nama_tipe . ' diperbarui',
        );
    }

    public function deleted(TipeKamar $tipeKamar): void
    {
        ActivityLogService::logModel(
            'deleted', 'tipe_kamar', $tipeKamar,
            'Tipe kamar ' . $tipeKamar->nama_tipe . ' dihapus',
        );
    }
}
