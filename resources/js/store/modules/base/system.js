const state = {
  snackbar: { 
    show: false,
    color: 'success',
    timeout: 5000,  
    message: '', 
    messages: [],
  },
  isSidebarOpen: null,
};

const getters = {
  snackbar: (state) => state.snackbar,
  isSidebarOpen: (state) => state.isSidebarOpen,
};

const mutations = {
  setSnackbar: (state, data) => state.snackbar = data, 
  resetSnackbar: (state, data) => state.snackbar = data,
  setIsSidebarOpen: (state, data) => state.isSidebarOpen = data,
};

const actions = {
  showSnackbar({commit, state}, data){
    commit('setSnackbar', { 
      show: true, 
      color: data.color || 'success', 
      timeout: data.timeout || 5000, 
      message: data.message,
      messages: data.messages,
    });
  },
  resetSnackbar({commit, state}, data){
    commit('setSnackbar', { 
      show: false, 
      color: 'success', 
      timeout: 5000,
      message: '', 
      messages: [],
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
