<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * ActivityLogService
 *
 * Service terpusat untuk mencatat semua aktivitas pengguna.
 * Dapat dipanggil dari Controller maupun Observer.
 *
 * Contoh penggunaan di Controller:
 *   ActivityLogService::log('created', 'booking', $booking->id, 'Booking baru dibuat: BK-001');
 *   ActivityLogService::log('updated', 'kamar', $kamar->id, 'Status kamar 101 diubah', $old, $new);
 *   ActivityLogService::log('login', 'auth', null, 'User login dari ' . request()->ip());
 */
class ActivityLogService
{
    /**
     * Catat aktivitas ke tabel activity_logs.
     *
     * @param  string       $action      Jenis aksi: created|updated|deleted|login|logout|exported|checkin|checkout
     * @param  string       $module      Nama modul/tabel: booking|kamar|tamu|user|auth|laporan
     * @param  int|null     $recordId    ID record yang terdampak
     * @param  string       $description Keterangan aktivitas yang mudah dibaca
     * @param  array|null   $oldData     Data sebelum perubahan (untuk update/delete)
     * @param  array|null   $newData     Data setelah perubahan (untuk create/update)
     */
    public static function log(
        string  $action,
        string  $module,
        ?int    $recordId,
        string  $description,
        ?array  $oldData = null,
        ?array  $newData = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => $action,
            'module'      => $module,
            'record_id'   => $recordId,
            'description' => $description,
            'old_data'    => $oldData,
            'new_data'    => $newData,
            'ip_address'  => Request::ip(),
            'user_agent'  => Request::userAgent(),
        ]);
    }

    /**
     * Helper: catat dari perubahan Eloquent model secara otomatis.
     * Ambil data lama dari $model->getOriginal() dan baru dari $model->getAttributes().
     * Hanya menyimpan field yang berubah (dirty fields).
     */
    public static function logModel(
        string $action,
        string $module,
        Model  $model,
        string $description
    ): ActivityLog {
        $oldData = null;
        $newData = null;

        if ($action === 'updated') {
            $dirtyKeys = array_keys($model->getDirty());
            $oldData   = collect($model->getOriginal())->only($dirtyKeys)->except(['password', 'remember_token'])->toArray();
            $newData   = collect($model->getAttributes())->only($dirtyKeys)->except(['password', 'remember_token'])->toArray();
        } elseif ($action === 'created') {
            $newData = collect($model->getAttributes())->except(['password', 'remember_token'])->toArray();
        } elseif ($action === 'deleted') {
            $oldData = collect($model->getAttributes())->except(['password', 'remember_token'])->toArray();
        }

        return static::log($action, $module, $model->getKey(), $description, $oldData, $newData);
    }

    /**
     * Shortcut: catat login user.
     */
    public static function logLogin(): void
    {
        static::log(
            'login',
            'auth',
            Auth::id(),
            'User "' . Auth::user()->name . '" berhasil login.',
        );
    }

    /**
     * Shortcut: catat logout user.
     */
    public static function logLogout(): void
    {
        if (Auth::check()) {
            static::log(
                'logout',
                'auth',
                Auth::id(),
                'User "' . Auth::user()->name . '" logout.',
            );
        }
    }

    /**
     * Shortcut: catat aktivitas export.
     */
    public static function logExport(string $module, string $format, string $keterangan = ''): void
    {
        static::log(
            'exported',
            $module,
            null,
            'Export data ' . $module . ' ke ' . strtoupper($format) . ($keterangan ? ': ' . $keterangan : ''),
        );
    }
}
