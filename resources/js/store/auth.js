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

    removeToken ({ commit }) {
      commit('SET_TOKEN', null)
      localStorage.removeItem('token')
      window.axios.defaults.headers.common.Authorization = ''
    },

    setUser ({ commit }, { user }) {
      commit('SET_USER', user)
    },

    async logout ({ dispatch }) {
      try {
        api.logout()
      } catch (error) {
        console.error(error)
      }
      dispatch('removeToken')
    },

    async checkLogged ({ dispatch }) {
      const token = localStorage.getItem('token')

      if (token) {
        try {
          await dispatch('setToken', { token })
          await dispatch('getUserCurrent')
        } catch (error) {
          console.error(error)
        }
      }
    },

    async getUserCurrent ({ dispatch }) {
      try {
        const { data } = await api.user()
        await dispatch('setUser', { user: data })
        return Promise.resolve(data)
      } catch (error) {
        localStorage.removeItem('token')
        await dispatch('removeToken')
        window.router.push({
          name: 'login'
        })
        return Promise.reject(error)
      }
    }
  }
}
