import httpService from '../../../services/http';
import helperMixins from '../../../helpers/mixins';

const state = {
  roles: null,
  rolePermissions: null,
  totalRoles: null,

  isLoadingGetRoles: false,
  isLoadingCreateRole: false,
  isLoadingUpdateRole: false,
  isLoadingGetRolePermissions: false,
  isLoadingSyncRolePermissions: false,
  isLoadingGetTotalRoles: false,
};

const getters = {
  roles: (state) => state.roles,
  rolePermissions: (state) => state.rolePermissions,
  totalRoles: (state) => state.totalRoles,

  isLoadingGetRoles: (state) => state.isLoadingGetRoles,
  isLoadingCreateRole: (state) => state.isLoadingCreateRole,
  isLoadingUpdateRole: (state) => state.isLoadingUpdateRole,
  isLoadingGetRolePermissions: (state) => state.isLoadingGetRolePermissions,
  isLoadingSyncRolePermissions: (state) => state.isLoadingSyncRolePermissions,
  isLoadingGetTotalRoles: (state) => state.isLoadingGetTotalRoles,
};

const mutations = {
  setRoles: (state, data) => state.roles = data,
  setRolePermissions: (state, data) => state.rolePermissions = data,
  setTotalRoles: (state, data) => state.totalRoles = data,
  setIncrementOnTotalRoles: (state, data) => {
    state.totalRoles = state.totalRoles ? parseInt(state.totalRoles) + 1 : 0;
  },
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
  getTotalRoles({commit, state}, data){
    state.isLoadingGetTotalRoles = true;

    return httpService.get('/roles/total')
      .then((response) => {
        const { data } = response;
        commit('setTotalRoles', data.total);

        return data;
      })
      .finally(() => {
        state.isLoadingGetTotalRoles = false;
      });
  },
  createRole({commit, state}, data){
    state.isLoadingCreateRole = true;

    return httpService.post('/roles', data)
      .then((response) => {
        const { data } = response;

        return data;
      })
      .finally(() => {
        state.isLoadingCreateRole = false;
      });
  },
  updateRole({commit, state}, data){
    state.isLoadingUpdateRole = true;

    const id = data.id;
    delete data.id;

    return httpService.put(`/roles/${id}`, data)
      .then((response) => {
        const { data } = response;

        return data;
      })
      .finally(() => {
        state.isLoadingUpdateRole = false;
      });
  },
  getRolePermissions({commit, state}, data){
    state.isLoadingGetRolePermissions = true;

    return httpService.get(`/roles/${data.id}/permissions`)
      .then((response) => {
        const { data } = response;
        commit('setRolePermissions', data);

        return data;
      })
      .finally(() => {
        state.isLoadingGetRolePermissions = false;
      });
  },
  syncRolePermissions({commit, state}, data){
    state.isLoadingSyncRolePermissions = true;

    const id = data.id;
    delete data.id;

    return httpService.put(`/roles/${id}/permissions`, data)
      .then((response) => {
        const { data } = response;

        return data;
      })
      .finally(() => {
        state.isLoadingSyncRolePermissions = false;
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
