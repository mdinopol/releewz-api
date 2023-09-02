<?php

if (!function_exists('rr')) {
    /**
     * Route role.
     *
     * @param App\Enum\Role $role
     *
     * @return string
     */
    function rr(App\Enum\Role $role): string
    {
        return "role:$role->value";
    }
}

if (!function_exists('is_local_or_testing')) {
    /**
     * @return bool
     */
    function is_local_or_testing(): bool
    {
        return app()->isLocal() || app()->runningUnitTests();
    }
}
