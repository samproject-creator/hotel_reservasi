<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    // Log bersifat immutable: tidak perlu updated_at
    public $timestamps = false;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'record_id',
        'description',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'old_data'   => 'array',
        'new_data'   => 'array',
        'created_at' => 'datetime',
    ];

    // Otomatis set created_at saat insert
    protected static function booted(): void
    {
        static::creating(function (ActivityLog $log) {
            $log->created_at ??= now();
        });
    }

    // --- Relasi ---
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(['name' => 'Sistem']);
    }

    // --- Scope filter ---
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByTanggal($query, string $dari, string $sampai)
    {
        return $query->whereBetween('created_at', [$dari . ' 00:00:00', $sampai . ' 23:59:59']);
    }

    // --- Accessor ---
    public function getActionBadgeAttribute(): string
    {
        return match ($this->action) {
            'created'  => '<span class="badge bg-success">Create</span>',
            'updated'  => '<span class="badge bg-warning text-dark">Update</span>',
            'deleted'  => '<span class="badge bg-danger">Delete</span>',
            'login'    => '<span class="badge bg-info">Login</span>',
            'logout'   => '<span class="badge bg-secondary">Logout</span>',
            'exported' => '<span class="badge bg-primary">Export</span>',
            'checkin'  => '<span class="badge bg-info">Check-In</span>',
            'checkout' => '<span class="badge bg-dark">Check-Out</span>',
            default    => '<span class="badge bg-light text-dark">' . ucfirst($this->action) . '</span>',
        };
    }
}
