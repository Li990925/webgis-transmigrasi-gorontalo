<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'kabupaten', 'jabatan', 'last_audit_seen_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_audit_seen_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Determine if user is Super Admin (Provinsi).
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Determine if user is Operator Kabupaten.
     */
    public function isOperatorKabupaten(): bool
    {
        return $this->role === 'operator_kabupaten';
    }

    /**
     * Determine if user is Eksekutif / Pimpinan (Read-Only).
     */
    public function isEksekutif(): bool
    {
        return $this->role === 'eksekutif';
    }

    /**
     * Check if user can manage data for a given kabupaten.
     */
    public function canManageKabupaten(?string $kabupaten): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (! $this->isOperatorKabupaten() || empty($this->kabupaten)) {
            return false;
        }

        // Normalize string comparison e.g. "Kabupaten Boalemo" vs "Boalemo"
        $userKab = strtolower(str_replace('kabupaten', '', $this->kabupaten));
        $targetKab = strtolower(str_replace('kabupaten', '', $kabupaten ?? ''));

        return trim($userKab) === trim($targetKab);
    }

    /**
     * Audit logs created by this user.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }
}
