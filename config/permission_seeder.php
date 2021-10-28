<?php

return [
  'permissions' => [
    // SETTINGS
    'setting_create',
    'setting_update',
    'setting_view',

    // ACTIVITY LOG
    'log_view',

    // USERS
    'user_create',
    'user_update',
    'user_view',
    'user_delete',
    'user_total_get',

    // PERMISSIONS
    'permission_create',
    'permission_update',
    'permission_view',
    'permission_delete',
    'permission_total_get',
    
    // ROLES
    'role_create',
    'role_update',
    'role_view',
    'role_delete',
    'role_permissions_get',
    'role_permissions_give',
    'role_permissions_sync',
    'role_total_get',
  ]
];