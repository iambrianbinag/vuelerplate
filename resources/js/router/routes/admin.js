import AppDashboard from '../../core/admin/pages/AppDashboard';
import AppUsers from '../../core/admin/pages/AppUsers';
import UsersForm from '../../core/admin/pages/UsersForm';

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
    name: 'users-create',
    component: UsersForm,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: '/users/:id/update',
    name: 'users-update',
    component: UsersForm,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: '/users/:id/view',
    name: 'users-view',
    component: UsersForm,
    meta: {
      requiresAuth: true,
    },
  },
];

export default routes;