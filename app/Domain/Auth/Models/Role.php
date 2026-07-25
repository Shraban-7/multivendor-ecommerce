<?php

namespace App\Domain\Auth\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    public function roleHasPermissions()
    {
        return $this->hasMany(RoleHasPermission::class, 'role_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id', 'permission_id');
    }

    public function permissionNames(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->permissions->pluck('name')->toArray()
        );
    }

    public function givePermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)
                ->where('guard_name', 'admin')
                ->firstOrFail();
        }

        $exists = RoleHasPermission::where('role_id', $this->id)
            ->where('permission_id', $permission->id)
            ->exists();

        if (! $exists) {
            RoleHasPermission::create([
                'role_id' => $this->id,
                'permission_id' => $permission->id,
            ]);
        }

        return true;
    }
}
