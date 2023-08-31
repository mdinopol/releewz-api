# User Roles

- predefined user roles that dictates routes authorization.
- roles authorization are ranked based on it's `level`:
    - high level role have authorization for low level roles.
    - lower level role do NOT have authorization for high level roles.
    - roles of the same level should NOT have authorization for each other.
- each user can only have 1 role.
- predefined roles are as follows (please check [Role Enum](../app/Enum/Role.php) for the definitions).

- route middleware are to be defined by using helper function `rr(Role::<Role>)`
    - e.g. `->middleware([rr(Role::ADMIN)])` - accessible by `admin and super_admin`; but not by `user`
