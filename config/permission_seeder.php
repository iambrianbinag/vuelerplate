<?php

return [
  'permissions' => [
    // SETTINGS
    [
      'name' => 'setting_create',
      'order' => 1.01
    ],
    [
      'name' => 'setting_update',
      'order' => 1.02
    ],
    [
      'name' => 'setting_view',
      'order' => 1.03
    ],

    // ACTIVITY LOG
    [
      'name' => 'log_view',
      'order' => 2.01
    ],

    // USERS
    [
      'name' => 'user_create',
      'order' => 3.01
    ],
    [
      'name' => 'user_update',
      'order' => 3.02
    ],
    [
      'name' => 'user_view',
      'order' => 3.03
    ],
    [
      'name' => 'user_delete',
      'order' => 3.04
    ],
    [
      'name' => 'user_total_get',
      'order' => 3.04
    ],

    // PERMISSIONS
    [
      'name' => 'permission_create',
      'order' => 4.01
    ],
    [
      'name' => 'permission_update',
      'order' => 4.02
    ],
    [
      'name' => 'permission_view',
      'order' => 4.03
    ],
    [
      'name' => 'permission_delete',
      'order' => 4.04
    ],
    [
      'name' => 'permission_total_get',
      'order' => 4.05
    ],
    
    // ROLES
    [
      'name' => 'role_create',
      'order' => 5.01
    ],
    [
      'name' => 'role_update',
      'order' => 5.02
    ],
    [
      'name' => 'role_view',
      'order' => 5.03
    ],
    [
      'name' => 'role_delete',
      'order' => 5.04
    ],
    [
      'name' => 'role_permissions_get',
      'order' => 5.05
    ],
    [
      'name' => 'role_permissions_give',
      'order' => 5.06
    ],
    [
      'name' => 'role_permissions_sync',
      'order' => 5.07
    ],
    [
      'name' => 'role_total_get',
      'order' => 5.07
    ],
  ]
];