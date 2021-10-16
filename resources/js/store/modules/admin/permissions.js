import httpService from '../../../services/http';
import helperMixins from '../../../helpers/mixins';

const state = {
  permissions: null,
  totalPermissions: null,

  isLoadingGetPermissions: false,
  isLoadingCreatePermission: false,
  isLoadingUpdatePermission: false,
  isLoadingGetTotalPermissions: false,
};

const getters = {
  permissions: (state) => state.permissions,
  totalPermissions: (state) => state.totalPermissions,

  isLoadingGetPermissions: (state) => state.isLoadingGetPermissions,
  isLoadingCreatePermission: (state) => state.isLoadingCreatePermission,
  isLoadingUpdatePermission: (state) => state.isLoadingUpdatePermission,
  isLoadingGetTotalPermissions: (state) => state.isLoadingGetTotalPermissions,
};

const mutations = {
  setPermissions: (state, data) => state.permissions = data,
  setTotalPermissions: (state, data) => state.totalPermissions = data,
  setIncrementOnTotalPermissions: (state, data) => {
    state.totalPermissions = state.totalPermissions ? parseInt(state.totalPermissions) + 1 : 0;
  },
};

const actions = {
  getPermissions({commit, state}, data){
    state.isLoadingGetPermissions = true;

    const params = helperMixins.methods.generateUrlParams(data);
    return httpService.get(`/permissions${params}`)
      .then((response) => {
        const { data } = response;
        commit('setPermissions', data);

        return data;
      })
      .finally(() => {
        state.isLoadingGetPermissions = false;
      });
  },
  getTotalPermissions({commit, state}, data){
    state.isLoadingGetTotalPermissions = true;

    return httpService.get('/permissions/total')
      .then((response) => {
        const { data } = response;
        commit('setTotalPermissions', data.total);

        return data;
      })
      .finally(() => {
        state.isLoadingGetTotalPermissions = false;
      });
  },
  createPermission({commit, state}, data){
    state.isLoadingCreatePermission = true;

    return httpService.post('/permissions', data)
      .then((response) => {
        const { data } = response;

        return data;
      })
      .finally(() => {
        state.isLoadingCreatePermission = false;
      });
  },
  updatePermission({commit, state}, data){
    state.isLoadingUpdatePermission = true;

    const id = data.id;
    delete data.id;

    return httpService.put(`/permissions/${id}`, data)
      .then((response) => {
        const { data } = response;

        return data;
      })
      .finally(() => {
        state.isLoadingUpdatePermission = false;
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
