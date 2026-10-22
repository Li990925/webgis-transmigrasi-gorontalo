<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action',
        'auditable_type',
        'auditable_id',
        'kawasan_nama',
        'kabupaten',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * User who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to record an audit log entry.
     */
    public static function record(
        string $action,
        ?Model $auditable = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $notes = null,
        ?User $user = null
    ): self {
        $currentUser = $user ?? auth()->user();

        return static::create([
            'user_id' => $currentUser?->id,
            'user_name' => $currentUser?->name ?? 'Sistem / Anonim',
            'user_role' => $currentUser?->role ?? 'tamu',
            'action' => strtoupper($action),
            'auditable_type' => $auditable ? get_class($auditable) : null,
            'auditable_id' => $auditable?->getKey(),
            'kawasan_nama' => $auditable && isset($auditable->nama) ? $auditable->nama : null,
            'kabupaten' => $auditable && isset($auditable->kabupaten) ? $auditable->kabupaten : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'user_agent' => request()->userAgent() ?? 'CLI / Browser',
            'notes' => $notes,
        ]);
    }
}
