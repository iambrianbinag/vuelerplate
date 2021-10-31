import httpService from '../../../services/http';

const state = {
  settings: null,

  isLoadingGetSettings: false,
  isLoadingCreateSetting: false,
  isLoadingUpdateSetting: false,
};

const getters = {
  settings: (state) => state.settings,

  isLoadingGetSettings: (state) => state.isLoadingGetSettings,
  isLoadingCreateSetting: (state) => state.isLoadingCreateSetting,
  isLoadingUpdateSetting: (state) => state.isLoadingUpdateSetting,
};

const mutations = {
  setSettings: (state, data) => state.settings = data,
  setAddedDataInSettings: (state, data) => {
    if(Array.isArray(state.settings)){
      state.settings.unshift(data);
    }
  },
  setUpdatedDataInSettings: (state, data) => {
    if(Array.isArray(state.settings)){
      state.settings = state.settings.map(setting => {
        if(setting.id === data.id){
          return data;
        }

        return setting;
      });
    }
  },
};

const actions = {
  getSettings({commit, state}, data){
    state.isLoadingGetSettings = true;

    return httpService.get('/settings')
      .then((response) => {
        const { data } = response;
        commit('setSettings', data);

        return data;
      })
      .finally(() => {
        state.isLoadingGetSettings = false;
      });
  },
  createSetting({commit, state}, data){
    state.isLoadingCreateSetting = true;

    return httpService.post('/settings', data)
      .then((response) => {
        const { data } = response;
        commit('setAddedDataInSettings', data);

        return data;
      })
      .finally(() => {
        state.isLoadingCreateSetting = false;
      });
  },
  updateSetting({commit, state}, data){
    state.isLoadingUpdateSetting = true;

    const id = data.id;

    return httpService.put(`/settings/${id}`, data)
      .then((response) => {
        const { data } = response;
        commit('setUpdatedDataInSettings', data);

        return data;
      })
      .finally(() => {
        state.isLoadingUpdateSetting = false;
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
