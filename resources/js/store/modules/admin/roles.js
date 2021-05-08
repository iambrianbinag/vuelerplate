import httpService from '../../../services/http';
import helperMixins from '../../../helpers/mixins';

const state = {
  roles: null,

  isLoadingGetRoles: false,
};

const getters = {
  roles: (state) => state.roles,

  isLoadingGetRoles: (state) => state.isLoadingGetRoles,
};

const mutations = {
  setRoles: (state, data) => state.roles = data,
};

const actions = {
  getRoles({commit, state}, data){
    state.isLoadingGetRoles = true;

    const params = helperMixins.methods.generateUrlParams(data);
    return httpService.get(`/roles${params}`)
      .then((response) => {
        const { data } = response;
        commit('setRoles', data);

        return data;
      })
      .finally(() => {
        state.isLoadingGetRoles = false;
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
