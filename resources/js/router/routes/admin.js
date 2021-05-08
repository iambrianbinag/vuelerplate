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
];

export default routes;