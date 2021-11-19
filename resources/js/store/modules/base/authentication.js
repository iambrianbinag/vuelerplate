import httpService from '../../../services/http';
import tokenService from '../../../services/token';
import helperMixins from '../../../helpers/mixins';

const state = {
  authenticatedUser: { token: null, tokenExpiration: null, information: null },

  isLoadingAuthenticatedUser: false,
  isLoadingUpdateUserAccount: false,
};

const getters = {
  authenticatedUser: (state) => state.authenticatedUser,
  authenticatedUserToken: (state) => state.authenticatedUser.token,
  authenticatedUserTokenExpiration: (state) => state.authenticatedUser.tokenExpiration,
  authenticatedUserInformation: (state) => state.authenticatedUser.information,

  isLoadingAuthenticatedUser: (state) => state.isLoadingAuthenticatedUser,
  isLoadingUpdateUserAccount: (state) => state.isLoadingUpdateUserAccount,
};

const mutations = {
  setAuthenticatedUser: (state, data) => state.authenticatedUser = data,
  setAuthenticatedUserToken: (state, data) => state.authenticatedUser.token = data,
  setAuthenticatedUserTokenExpiration: (state, data) => state.authenticatedUser.tokenExpiration = data,
  setAuthenticatedUserInformation: (state, data) => state.authenticatedUser.information = data,
};

const actions = {
  login({commit, state}, data){
    state.isLoadingAuthenticatedUser = true;

    return httpService.post('/users/login', data)
      .then((response) => {
        const { access_token, expires_in } = response.data;
        const tokenExpiration = helperMixins.methods.addSecondsAtCurrentDate(expires_in);

        tokenService.saveToken(access_token, expires_in);
        tokenService.saveTokenExpirationDate(tokenExpiration, expires_in);
        httpService.setHeader();
        commit('setAuthenticatedUserToken', access_token);
        commit('setAuthenticatedUserTokenExpiration', tokenExpiration);

        return response.data;
      })    
      .finally(() => {
        state.isLoadingAuthenticatedUser = false;
      });
  },
  refresh({commit, state}, data){
    state.isLoadingAuthenticatedUser = true;

    return httpService.post('/users/refresh', data)
      .then((response) => {
        const { access_token, expires_in } = response.data;
        const tokenExpiration = helperMixins.methods.addSecondsAtCurrentDate(expires_in);

        tokenService.saveToken(access_token, expires_in);
        tokenService.saveTokenExpirationDate(tokenExpiration, expires_in);
        httpService.setHeader();
        commit('setAuthenticatedUserToken', access_token);
        commit('setAuthenticatedUserTokenExpiration', tokenExpiration);

        return response.data;
      }).finally(() => {
        state.isLoadingAuthenticatedUser = false;
      });
  },
  logout({commit, state}, data){
    state.isLoadingAuthenticatedUser = true;

    return httpService.post('/users/logout', data)
      .then((response) => {
        tokenService.removeToken();
        tokenService.removeTokenExpirationDate();
        httpService.removeHeader();
        commit('setAuthenticatedUser', { token: null, tokenExpiration: null, information: null });

        return response.data;
      }).finally(() => {
        state.isLoadingAuthenticatedUser = false;
      });
  },
  getAuthenticatedUser({commit, state}, data){
    state.isLoadingAuthenticatedUser = true;
    
    return httpService.post('/users/me', data)
      .then((response) => {
        const data = response.data;
        commit('setAuthenticatedUserInformation', data);

        return data;
      }).finally(() => {
        state.isLoadingAuthenticatedUser = false;
      });
  },
  updateUserAccount({commit, state, getters}, data){
    state.isLoadingUpdateUserAccount = true;

    return httpService.post('/users/my-account', data)
      .then((response) => {
        const data = response.data;
        const updatedAuthenticatedUserInformation = {...getters.authenticatedUserInformation, ...data};
        commit('setAuthenticatedUserInformation', updatedAuthenticatedUserInformation);

        return data;
      }).finally(() => {
        state.isLoadingUpdateUserAccount = false;
      });
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
