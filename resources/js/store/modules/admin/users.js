import httpService from '../../../services/http';
import helperMixins from '../../../helpers/mixins';

const state = {
  users: null,
  user: null,
  totalUsers: null,

  isLoadingGetUsers: false,
  isLoadingGetUser: false,
  isLoadingCreateUser: false,
  isLoadingUpdateUser: false,
  isLoadingGetTotalUsers: false,
};

const getters = {
  users: (state) => state.users,
  totalUsers: (state) => state.totalUsers,

  isLoadingGetUsers: (state) => state.isLoadingGetUsers,
  isLoadingGetUser: (state) => state.isLoadingGetUser,
  isLoadingCreateUser: (state) => state.isLoadingCreateUser,
  isLoadingUpdateUser: (state) => state.isLoadingUpdateUser,
  isLoadingGetTotalUsers: (state) => state.isLoadingGetTotalUsers,
};

const mutations = {
  setUsers: (state, data) => state.users = data,
  setUser: (state, data) => state.user = data,
  setTotalUsers: (state, data) => state.totalUsers = data,
  setIncrementOnTotalUsers: (state, data) => {
    state.totalUsers = state.totalUsers ? parseInt(state.totalUsers) + 1 : 0;
  },
}

const actions = {
  getUsers({commit, state}, data){
    state.isLoadingGetUsers = true;

    const params = helperMixins.methods.generateUrlParams(data);
    return httpService.get(`/users${params}`)
      .then((response) => {
        const { data } = response;
        commit('setUsers', data);

        return data;
      })
      .finally(() => {
        state.isLoadingGetUsers = false;
      });
  },
  getUser({commit, state}, data){
    state.isLoadingGetUser = true;

    return httpService.get(`/users/${data.id}`)
      .then((response) => {
        const { data } = response;
        commit('setUser', data);

        return data;
      })
      .finally(() => {
        state.isLoadingGetUser = false;
      });
  },
  getTotalUsers({commit, state}, data){
    state.isLoadingGetTotalUsers = true;
    
    return httpService.get('/users/total')
      .then((response) => {
        const { data } = response;
        commit('setTotalUsers', data.total);

        return data;
      })
      .finally(() => {
        state.isLoadingGetTotalUsers = false;
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
  updateUser({commit, state}, data){
    state.isLoadingUpdateUser = true;
    
    const id = data.id;
    delete data.id;

    return httpService.put(`/users/${id}`, data)
      .then((response) => {
        const { data } = response;
       
        return data;
      })
      .finally(() => {
        state.isLoadingUpdateUser = false;
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
