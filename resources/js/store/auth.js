import * as api from '~/api'

export default {
  namespaced: true,

  state: () => ({
    token: null,
    user: null
  }),
  
  getters: {
    token: state => state.token,
    user: state => state.user,
    logged: state => !!state.token,
    role: state => _get(state, 'user.role', '')
  },

  mutations: {
    SET_TOKEN (state, payload) {
      state.token = payload
    },
    SET_USER (state, payload) {
      state.user = payload
    }
  },
  actions: {
    setToken ({ commit }, { token }) {
      commit('SET_TOKEN', token)
      localStorage.setItem('token', token)
      window.axios.defaults.headers.common.Authorization = `Bearer ${token}`
    },

    setUser ({ commit }, { user }) {
      commit('SET_USER', user)
    },

    async logout ({ commit }) {
      try {
        api.logout()
      } catch (error) {
        console.error(error)
      }
      commit('SET_TOKEN', null)
      localStorage.removeItem('token')
      window.axios.defaults.headers.common.Authorization = ''
    },

    checkLogged ({ dispatch }) {
      const token = localStorage.getItem('token')

      if (token) {
        dispatch('setToken', { token })
        dispatch('getUserCurrent')
      }
    },

    async getUserCurrent ({ dispatch }) {
      try {
        const { data } = await api.user()
        dispatch('setUser', { user: data })
      } catch (error) {
        console.error(error)
      }
    }
  }
}
