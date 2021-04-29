import AppDashboard from '../../core/admin/pages/AppDashboard';
import AppUsers from '../../core/admin/pages/AppUsers';

const routes = [
  {
    path: '/home',
    name: 'home',
    component: AppDashboard,
    meta: {
      requiresAuth: true,
      showInSideBar: true,
      icon: 'baby-carriage-off',
      label: 'Home'
    }
  },
  {
    path: '/users',
    name: 'users',
    component: AppUsers,
    meta: {
      requiresAuth: true,
      showInSideBar: true,
      icon: 'airballoon',
      label: 'Users'
    },
    children: [
      {
        path: 'list',
        name: 'users-list',
        component: AppDashboard,
        meta: {
          requiresAuth: true,
          showInSideBar: true,
          icon: 'drama-masks',
          label: 'List'
        },
      },
      {
        name: 'users-create',
        path: 'create',
        component: AppDashboard,
        meta: {
          requiresAuth: true,
          showInSideBar: true,
          icon: 'bank',
          label: 'Create'
        },
      },
    ]
  },
];

export default routes;