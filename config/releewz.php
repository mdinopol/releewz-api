<?php

use App\Enum\Role;

return [
    /*
     * Any role levels that is >= the specified is considered super admin.
     */
    'super_admin_min_level' => 99,

    /*
     * Default registration role.
     */
    'default_registration_role' => Role::USER,

    /*
     * Maximum entries required before a game can proceed to OPEN_REGISTRATION state.
     */
    'min_game_entry' => 5,
];
