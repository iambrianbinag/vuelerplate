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
 * AUDIT TRAIL
 */
import SystemLog from '../../core/admin/pages/system-log/SystemLog';

/**
 * CONTROL PANEL
 */
import ControlPanel from '../../core/admin/pages/control-panel/ControlPanel';

const routes = [
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
   * CONTROL PANEL
   */
  {
    path: '/settings',
    name: 'settings',
    redirect: {
      name: 'control-panel-list'
    },
  },
  {
    path: '/settings/control-panel',
    name: 'control-panel-list',
    component: ControlPanel,
    meta: {
      requiresAuth: true,
    }
  }
];

export default routes;