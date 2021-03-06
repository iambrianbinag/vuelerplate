/**
 * ACCOUNTS
 */
import UserAccount from '../../core/admin/pages/accounts/UserAccount';

/**
 * DASHBOARD
 */
import AppDashboard from '../../core/admin/pages/dashboard/AppDashboard';
/**
 * USERS
 */
import AppUsers from '../../core/admin/pages/users/AppUsers';
import UserForm from '../../core/admin/pages/users/UserForm';
import UserView from '../../core/admin/pages/users/UserView';
/**
 * ROLES
 */
import AppRoles from '../../core/admin/pages/roles/AppRoles';
/**
 * PERMISSIONS
 */
import AppPermissions from '../../core/admin/pages/permissions/AppPermissions';
/**
 * SYSTEM LOG
 */
import SystemLog from '../../core/admin/pages/system-log/SystemLog';

/**
 * SETTINGS
 */
import AppSettings from '../../core/admin/pages/settings/AppSettings';

const routes = [
  /**
   * ACCOUNTS
   */
  {
    path: '/my-account',
    name: 'my-account',
    component: UserAccount,
    meta: {
      requiresAuth: true,
    },
  },
  /**
   * DASHBOARD
   */
  {
    path: '/home',
    name: 'home',
    component: AppDashboard,
    meta: {
      requiresAuth: true,
    }
  },
  /**
   * USERS
   */
  {
    path: '/users',
    name: 'users',
    redirect: { 
      name: 'user-list' 
    },
  },
  {
    path: '/users/list',
    name: 'user-list',
    component: AppUsers,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: '/users/create',
    name: 'user-create',
    component: UserForm,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: '/users/:id/update',
    name: 'user-update',
    component: UserForm,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: '/users/:id/view',
    name: 'user-view',
    component: UserView,
    meta: {
      requiresAuth: true,
    },
  },
  /**
   * ROLES
   */
  {
    path: '/users/roles',
    name: 'role-list',
    component: AppRoles,
    meta: {
      requiresAuth: true,
    },
  },
  /**
   * PERMISSIONS
   */
  {
    path: '/users/permissions',
    name: 'permission-list',
    component: AppPermissions,
    meta: {
      requiresAuth: true,
    },
  },
  /**
   * AUDIT TRAIL
   */
  {
    path: '/audit-trail',
    name: 'audit-trail',
    redirect: {
      name: 'system-log-list'
    }
  },
  {
    path: '/audit-trail/system-log',
    name: 'system-log-list',
    component: SystemLog,
    meta: {
      requiresAuth: true,
    }
  },
  /**
   * SETTINGS
   */
  {
    path: '/control-panel',
    name: 'control-panel',
    redirect: {
      name: 'setting-list'
    },
  },
  {
    path: '/control-panel/settings',
    name: 'setting-list',
    component: AppSettings,
    meta: {
      requiresAuth: true,
    }
  }
];

export default routes;