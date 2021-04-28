import axios from 'axios';
import tokenService from './token';
import router from '../router';
import store from '../store';

/**
 * HTTP service
 */
const http = {
  /**
   * Initialize http base url and store's token
   * 
   * @param {String} baseURL base url 
   */
  initialize(baseURL){
    axios.defaults.baseURL = baseURL;
    store.commit('base.authentication/setAuthenticatedUserToken', tokenService.getToken());
    store.commit('base.authentication/setAuthenticatedUserTokenExpiration', tokenService.getTokenExpirationDate());
  },
  /**
   * Set http header
   */
  setHeader(){
    axios.defaults.headers.common['Content-Type'] = 'application/json';
    axios.defaults.headers.common['Authorization'] = `Bearer ${tokenService.getToken()}`;
  },
  /**
   * Remove http header
   */
  removeHeader(){
    axios.defaults.headers.common = {};
    axios.defaults.headers.common['Content-Type'] = 'application/json';
  },
  /**
   * Mount http interceptors and execute process based on response status
   */
  mountErrorInterceptor(){
    axios.interceptors.response.use(null, async (error) => {
      switch(error.response.status){
        case 401:
          tokenService.removeToken();
          tokenService.removeTokenExpirationDate();
          store.commit('base.authentication/setAuthenticatedUserToken', null);
          store.commit('base.authentication/setAuthenticatedUserTokenExpiration', null);
          this.removeHeader();
          router.push({ name: 'login' });
          break;
        case 422:
          console.log('Error 422', error.response.data);
          // TODO error parameters message
          break;
        case 500:
          store.commit('base.system/setServerError', { 
            messages: [
              'Sorry, something went wrong. Seems like we have an internal server error. Please try again later or report this issue.'
            ] 
          });
          break;
        default:
          // Do nothing...
      }

      setTimeout(() => store.commit('base.system/resetServerError'), 3000);

      throw error;
    });
  },
  get: axios.get,
  post: axios.post,
  put: axios.put,
  delete: axios.delete,
}

export default http;