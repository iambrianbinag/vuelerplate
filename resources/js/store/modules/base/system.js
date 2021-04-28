const state = {
  serverError: { isError: false, messages: [] },
  appName: null,
};

const getters = {
  serverError: (state) => state.serverError,
  appName: (state) => state.appName
};

const mutations = {
  setServerError: (state, data) => state.serverError = { isError: true, messages: data.messages },
  resetServerError: (state) => state.serverError = { isError: false, messages: [] },
  setAppName: (state, data) => state.appName = data,
};

const actions = {
  //
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
