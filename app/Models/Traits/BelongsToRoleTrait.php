<?php

namespace App\Models\Traits;

use App\Enum\Role;

trait BelongsToRoleTrait
{
    /**
     * @param string $role the minimum required role for the action
     */
    public function hasRoleAuthorization(string $role): bool
    {
        if ($requiredRoleForAction = Role::tryFrom($role)) {
            return $this->role->value === $requiredRoleForAction->value
                || $this->role->level() > $requiredRoleForAction->level();
        }

        throw new \UnexpectedValueException('Role name not found.');
    }

    // public function isSuperAdmin(): bool
    // {
    //     return $this->role->level() >= config('releewz.super_admin_min_level');
    // }
}
