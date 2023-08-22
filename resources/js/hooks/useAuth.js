import { computed } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

const ROLES = {
  ADMIN: 'ADMIN',
  USER: 'USER'
}
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
    router.replace({
      name: 'tests'
    })
  }

  const redirectToLogin = () => {
    router.replace({
      name: 'login'
    })
  }

  const logout = () => {
    redirectToLogin()
    store.dispatch('auth/logout')
  }

  const role = computed(() => store.getters['auth/role'])
  const isAdmin = computed(() => store.getters['auth/role'] === ROLES.ADMIN)

  return {
    logged: computed(() => store.getters['auth/logged']),
    user: computed(() => store.getters['auth/user']),
    role,
    ROLES,
    isAdmin,
    setToken,
    setUser,
    logout,
    redirectToCabinet,
    redirectToLogin
  }
}
