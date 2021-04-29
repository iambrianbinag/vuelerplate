const state = {
  serverError: { isError: false, messages: [] },
  appName: null,
  isSidebarOpen: null,
};

const getters = {
  serverError: (state) => state.serverError,
  appName: (state) => state.appName,
  isSidebarOpen: (state) => state.isSidebarOpen,
};

const mutations = {
  setServerError: (state, data) => state.serverError = { isError: true, messages: data.messages },
  resetServerError: (state) => state.serverError = { isError: false, messages: [] },
  setAppName: (state, data) => state.appName = data,
  setIsSidebarOpen: (state, data) => state.isSidebarOpen = data,
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
