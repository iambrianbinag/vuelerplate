import httpService from '../../../services/http';
import helperMixins from '../../../helpers/mixins';

const state = {
  log: null,

  isLoadingGetLog: false,
};

const getters = {
  log: (state) => state.log,

  isLoadingGetLog: (state) => state.isLoadingGetLog,
};

const mutations = {
  setLog: (state, data) => state.log = data,
};

const actions = {
  getLog({commit, state}, data){
    state.isLoadingGetLog = true;

    const params = helperMixins.methods.generateUrlParams(data);
    return httpService.get(`/activity-log${params}`)
      .then((response) => {
        const { data } = response;
        commit('setLog', data);

        return data;
      })
      .finally(() => {
        state.isLoadingGetLog = false;
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
