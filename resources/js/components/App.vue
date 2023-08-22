<template>
  <v-app>
    <v-app-bar
      color="red"
      scroll-behavior="elevate fade-image inverted"
      image="https://picsum.photos/1920/1080?random"
    >
      <template
        v-if="logged"
        #prepend
      >
        <v-app-bar-nav-icon />
        <v-menu activator="parent">
          <v-list>
            <router-link
              v-for="(item, index) in items"
              :key="index"
              :value="index"
              :to="{ name: item.name }"
              class="text-body-1 text-black text-decoration-none"
            >
              <v-list-item>
                <v-list-item-title>{{ item.label }}</v-list-item-title>
              </v-list-item>
            </router-link>
          </v-list>
        </v-menu>
      </template>
      <v-app-bar-title>
        TestMe <span
          v-if="logged"
          class="text-subtitle-1 font-weight-bold"
        >{{ _get(user, 'name', '') || '-' }}</span>
      </v-app-bar-title>
      <v-btn
        v-if="logged"
        icon
      >
        <v-icon>mdi-heart</v-icon>
      </v-btn>
      <button
        v-if="logged"
        class="mr-5"
        @click="logout"
      >
        {{ $t('exit') }}
      </button>
    </v-app-bar>

    <v-main>
      <Suspense>
        <router-view class="page-content" />
      </Suspense>
    </v-main>
  </v-app>
</template>
<script>
  import { ref } from 'vue'
  // import { useRouter } from 'vue-router'
  import { useI18n } from 'vue3-i18n'
  import { useAuth } from '~/hooks/useAuth'

  export default {
    setup () {
      const { logged, user, logout } = useAuth()
      // const router = useRouter()
      const { t: $t } = useI18n()

      const items = ref([
        {
          label: $t('menu.tests'),
          path: '/cabinet/tests',
          name: 'tests'
        },
        {
          label: $t('menu.results'),
          path: '/cabinet/results',
          name: 'results'
        },
        {
          label: $t('menu.users'),
          path: '/cabinet/users',
          name: 'users'
        },
        {
          label: $t('menu.settings'),
          path: '/cabinet/settings',
          name: 'settings'
        }
      ])

      return {
        items,
        logged,
        user,
        logout
      }
    }
  }
</script>
