import AppDashboard from '../../core/admin/pages/AppDashboard';
import AppUsers from '../../core/admin/pages/AppUsers';

const routes = [
  {
    path: '/home',
    name: 'home',
    component: AppDashboard,
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/users',
    name: 'users',
    component: AppUsers,
    meta: {
      requiresAuth: true
    }
  },
];

export default routes;