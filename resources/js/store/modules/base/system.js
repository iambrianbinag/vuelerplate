const state = {
  serverError: { isError: false, messages: [] },
};

const getters = {
  serverError: (state) => state.serverError,
};

const mutations = {
  setServerError: (state, data) => state.serverError = { isError: true, messages: data.messages },
  resetServerError: (state) => state.serverError = { isError: false, messages: [] },
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
