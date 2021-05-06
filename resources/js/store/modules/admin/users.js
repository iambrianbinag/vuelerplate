import httpService from '../../../services/http';
import helperMixins from '../../../helpers/mixins';

const state = {
  users: null,

  isLoadingUsers: false,
  isLoadingCreateUser: false,
};

const getters = {
  users: (state) => state.users,

  isLoadingUsers: (state) => state.isLoadingUsers,
  isLoadingCreateUser: (state) => state.isLoadingCreateUser,
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
  },
  createUser({commit, state}, data){
    state.isLoadingCreateUser = true;

    return httpService.post('/users', data)
      .then((response) => {
        const { data } = response;
       
        return data;
      })
      .finally(() => {
        state.isLoadingCreateUser = false;
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
