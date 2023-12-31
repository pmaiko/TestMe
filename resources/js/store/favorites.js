export default {
  namespaced: true,

  state: () => ({
    favorites: null,
    loading: false
  }),
  
  getters: {
    favorites: state => state.favorites,
    favoritesIds: state => state.favorites?.map((favorite) => {
      return _get(favorite, 'question.id', '')
    }),
    loading: state => state.loading
  },

  mutations: {
    SET_FAVORITES (state, payload) {
      state.favorites = payload
    },

    SET_LOADING (state, payload) {
      state.loading = payload
    }
  },

  actions: {
    async fetch ({ state, commit }, { forceLoad } = {}) {
      if (!state.loading && (!state.favorites || forceLoad)) {
        try {
          commit('SET_LOADING', true)

          const { data } = await useApi().favorites()
          commit('SET_FAVORITES', _get(data, 'data.length', '') ? data.data : null)
        } catch (event) {
          //
        } finally {
          commit('SET_LOADING', false)
        }
      }
    }
  }
}
