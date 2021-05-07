import AppDashboard from '../../core/admin/pages/AppDashboard';
import AppUsers from '../../core/admin/pages/AppUsers';
import UserForm from '../../core/admin/pages/UserForm';

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
      name: 'users-list' 
    },
  },
  {
    path: '/users/list',
    name: 'users-list',
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
    component: UserForm,
    meta: {
      requiresAuth: true,
    },
  },
];

export default routes;