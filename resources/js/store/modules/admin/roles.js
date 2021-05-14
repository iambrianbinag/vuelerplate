import httpService from '../../../services/http';
import helperMixins from '../../../helpers/mixins';

const state = {
  roles: null,
  rolePermissions: null,

  isLoadingGetRoles: false,
  isLoadingCreateRole: false,
  isLoadingUpdateRole: false,
  isLoadingGetRolePermissions: false,
};

const getters = {
  roles: (state) => state.roles,

  isLoadingGetRoles: (state) => state.isLoadingGetRoles,
  isLoadingCreateRole: (state) => state.isLoadingCreateRole,
  isLoadingUpdateRole: (state) => state.isLoadingUpdateRole,
  isLoadingGetRolePermissions: (state) => state.isLoadingGetRolePermissions,
};

const mutations = {
  setRoles: (state, data) => state.roles = data,
  setRolePermissions: (state, data) => state.rolePermissions = data,
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
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
