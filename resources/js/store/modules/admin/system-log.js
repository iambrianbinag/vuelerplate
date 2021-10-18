import httpService from '../../../services/http';
import helperMixins from '../../../helpers/mixins';

const state = {
  systemLog: null,

  isLoadingGetSystemLog: false,
};

const getters = {
  systemLog: (state) => state.systemLog,

  isLoadingGetSystemLog: (state) => state.isLoadingGetSystemLog,
};

const mutations = {
  setSystemLog: (state, data) => state.systemLog = data,
  setFirstDataAndRemoveLastDataInSystemLog: (state, data) => {
    if(!state.systemLog && !state.systemLog.data && !state.systemLog.data.length > 0){
      return;
    }

    const updatedData = [...state.systemLog.data];
    updatedData.unshift(data);
    updatedData.pop();

    state.systemLog.data = updatedData;
  },
};

const actions = {
  getSystemLog({commit, state}, data){
    state.isLoadingGetSystemLog = true;

    const params = helperMixins.methods.generateUrlParams(data);
    return httpService.get(`/activity-log${params}`)
      .then((response) => {
        const { data } = response;
        commit('setSystemLog', data);

        return data;
      })
      .finally(() => {
        state.isLoadingGetSystemLog = false;
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
