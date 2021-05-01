import httpService from '../../../services/http';
import helperMixins from '../../../helpers/mixins';

const state = {
  users: null,

  isLoadingUsers: false,
};

const getters = {
  users: (state) => state.users,

  isLoadingUsers: (state) => state.isLoadingUsers,
};

const mutations = {
  setUsers: (state, data) => state.users = data,
};

const actions = {
  getUsers({commit, state}, data){
    state.isLoadingUsers = true;

    const params = helperMixins.methods.generateUrlParams(data);
    return httpService.get(`/users${params}`)
      .then((response) => {
        const { data } = response;
        commit('setUsers', data);

        return data;
      })
      .finally(() => {
        state.isLoadingUsers = false;
      });
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
