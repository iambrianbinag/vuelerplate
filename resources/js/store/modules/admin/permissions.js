import httpService from '../../../services/http';
import helperMixins from '../../../helpers/mixins';

const state = {
  permissions: null,

  isLoadingGetPermissions: false,
  isLoadingCreatePermission: false,
  isLoadingUpdatePermission: false,
};

const getters = {
  permissions: (state) => state.permissions,

  isLoadingGetPermissions: (state) => state.isLoadingGetPermissions,
  isLoadingCreatePermission: (state) => state.isLoadingCreatePermission,
  isLoadingUpdatePermission: (state) => state.isLoadingUpdatePermission,
};

const mutations = {
  setPermissions: (state, data) => state.permissions = data,
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
