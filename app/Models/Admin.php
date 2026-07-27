<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admin';

    protected $permissionCache = null;

    protected $fillable = [
        'nama_admin',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function permissions()
    {
        return $this->hasMany(AdminPermission::class, 'admin_id');
    }

    public function isSuperAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'administrator'], true);
    }

    public function isAdmin(): bool
    {
        return ! $this->isSuperAdmin();
    }

    public function getRoleLabelAttribute(): string
    {
        return $this->isSuperAdmin() ? 'Super Admin' : 'Admin';
    }

    public function permissionKeys(): array
    {
        if ($this->isSuperAdmin()) {
            return \App\Support\AdminPermission::keys();
        }

        if ($this->relationLoaded('permissions')) {
            return $this->permissions->pluck('permission')->all();
        }

        if ($this->permissionCache === null) {
            $this->permissionCache = $this->permissions()->pluck('permission')->all();
        }

        return $this->permissionCache;
    }

    public function hasPermission(string $permission): bool
    {
        return $this->isSuperAdmin() || in_array($permission, $this->permissionKeys(), true);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return count(array_intersect($permissions, $this->permissionKeys())) > 0;
    }

    public function syncPermissions(array $permissions): void
    {
        $this->permissions()->delete();

        if ($this->isSuperAdmin()) {
            $this->permissionCache = null;
            return;
        }

        $now = now();
        $rows = collect($permissions)
            ->unique()
            ->values()
            ->map(fn (string $permission) => [
                'admin_id' => $this->id,
                'permission' => $permission,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->all();

        if ($rows) {
            $this->permissions()->insert($rows);
        }

        $this->permissionCache = null;
    }
}
