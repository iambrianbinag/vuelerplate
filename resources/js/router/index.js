import Router from 'vue-router';
import routes from './routes';
import store from '../store';

const router = new Router({
  mode: 'history',
  routes
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = store.getters['base.authentication/authenticatedUserToken'];

  if(to.name == 'login' && isAuthenticated){
    next({ name: 'home'});
  }

  if(to.matched.some(record => record.meta.requiresAuth)){
    if(isAuthenticated){
      next();
    } else {
      next({ name: 'login', params: { nextNamedUrl: to.name } });
    }
  } else {
    next();
  }
});

export default router;