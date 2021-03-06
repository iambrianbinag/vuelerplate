import AppLogin from 'base/pages/AppLogin';
import NotFound from 'base/pages/NotFound';

const routes = [
  {
    path: '/',
    redirect: { name: 'login' }
  },
  {
    path: '/login',
    name: 'login',
    component: AppLogin,
    meta: {
      requiresAuth: false
    }
  },
  {
    path: '*',
    name: '404',
    component: NotFound,
    meta: {
      requiresAuth: false
    }
  },
];

export default routes;