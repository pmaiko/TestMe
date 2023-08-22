import { computed } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

export const useAuth = () => {
  const store = useStore()
  const router = useRouter()

  const setToken = (token) => {
    store.dispatch('auth/setToken', {
      token
    })
  }

  const setUser = (user) => {
    store.dispatch('auth/setUser', {
      user
    })
  }

  const redirectToCabinet = () => {
    router.replace('/cabinet')
  }

  const redirectToMain = () => {
    router.replace('/')
  }

  const logout = () => {
    redirectToMain()
    store.dispatch('auth/logout')
  }

  return {
    logged: computed(() => store.getters['auth/logged']),
    user: computed(() => store.getters['auth/user']),
    setToken,
    setUser,
    logout,
    redirectToCabinet,
    redirectToMain
  }
}
