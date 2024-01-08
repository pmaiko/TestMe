<template>
  <v-app>
    <v-navigation-drawer
      v-if="logged"
      v-model="drawer"
      location="left"
      :temporary="$vuetify.display.smAndDown"
      theme="red"
    >
      <v-list nav>
        <v-list-item
          v-for="(item, index) in items"
          :key="index"
          :link="true"
          :to="item.path"
          :title="item.label"
          :value="item.label"
          :prepend-icon="item.icon"
        />
      </v-list>
    </v-navigation-drawer>
    <v-app-bar
      color="red"
      scroll-behavior="elevate fade-image inverted"
    >
      <!--image="https://picsum.photos/1920/1080?random"-->
      <template
        v-if="logged"
        #prepend
      >
        <v-app-bar-nav-icon
          @click.stop="drawer = !drawer"
        />
        <!--image="https://cdn.vuetifyjs.com/images/backgrounds/bg-2.jpg"-->
      </template>

      <v-app-bar-title>
        TestMe <span
          v-if="logged"
          class="text-subtitle-1 font-weight-bold"
        >{{ _get(user, 'name', '') || '-' }}</span>
      </v-app-bar-title>

      <v-switch
        :model-value="themeModel"
        true-value="light"
        false-value="dark"
        hide-details
        :label="$t('theme')"
        class="mr-4 ml-auto flex-0-0"
        @update:model-value="toggleTheme"
      />

      <button
        v-if="logged"
        class="mr-4"
        @click="logout"
      >
        {{ $t('exit') }}
      </button>
    </v-app-bar>

    <v-main class="mb-8">
      <router-view class="page-content" />
    </v-main>

    <v-snackbar
      v-model="snackbar"
      :timeout="4000"
    >
      {{ snackbarText }}

      <template #actions>
        <v-btn
          color="blue"
          variant="text"
          @click="snackbar = false"
        >
          {{ $t('close') }}
        </v-btn>
      </template>
    </v-snackbar>
  </v-app>
</template>
<script setup>
  import { useTheme } from 'vuetify'

  const { logged, user, logout } = useAuth()
  const router = useRouter()
  const store = useStore()

  const theme = useTheme()
  const themeModel = ref(localStorage.getItem('theme') || null)
  function toggleTheme () {
    theme.global.name.value = theme.global.current.value.dark ? 'light' : 'dark'
    themeModel.value = theme.global.name.value
    localStorage.setItem('theme', theme.global.name.value)
  }
  
  const items = ref([
    {
      label: $t('menu.tests'),
      path: '/cabinet/tests',
      name: 'tests',
      icon: 'mdi-ab-testing'
    },
    {
      label: $t('menu.results'),
      path: '/cabinet/results',
      name: 'results',
      icon: 'mdi-chart-line'
    },
    {
      label: $t('menu.favorites'),
      path: '/cabinet/favorites',
      name: 'favorites',
      icon: 'mdi-heart-outline'
    },
    {
      label: $t('menu.users'),
      path: '/cabinet/users',
      name: 'users',
      icon: 'mdi-account-multiple-outline'
    },
    {
      label: $t('menu.settings'),
      path: '/cabinet/settings',
      name: 'settings',
      icon: 'mdi-cog'
    }
  ])

  const snackbar = ref(false)
  const snackbarText = ref(false)
  const drawer = ref(false)

  const showSnackbar = (text) => {
    snackbar.value = true
    snackbarText.value = text
  }
  provide('showSnackbar', showSnackbar)

  router.beforeEach(async (to, from) => {
    await store.dispatch('auth/checkLogged')

    const logged = store.getters['auth/logged']
    if (!logged && to.name !== 'login') {
      return {
        name: 'login'
      }
    } if (logged && to.name === 'login') {
      return {
        name: 'tests'
      }
    }

    const role = store.getters['auth/role']
    if (to.meta.admin && role !== 'ADMIN') {
      return {
        name: 'forbidden'
      }
    }

    if (logged) {
      store.dispatch('favorites/fetch')
    }
  })
</script>
<style lang="scss">
  .pointer-events-none {
    pointer-events: none;
  }
</style>
