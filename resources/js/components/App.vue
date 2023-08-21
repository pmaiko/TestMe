<template>
  <v-app>
    <v-app-bar
      title="TestMe"
      color="red"
      scroll-behavior="elevate fade-image inverted"
      image="https://picsum.photos/1920/1080?random"
    >
      <template #prepend>
        <v-app-bar-nav-icon />
        <v-menu activator="parent">
          <v-list>
            <v-list-item
              v-for="(item, index) in items"
              :key="index"
              :value="index"
            >
              <v-list-item-title>{{ item.label }}</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </template>

      <button @click="logout">
        Вийти
      </button>
      <v-btn icon>
        <v-icon>mdi-heart</v-icon>
      </v-btn>
    </v-app-bar>

    <v-main>
      <router-view />
    </v-main>
  </v-app>
</template>
<script>
  import { ref } from 'vue'
  import { useStore } from 'vuex'
  import { useRouter } from 'vue-router'

  export default {
    setup () {
      const store = useStore()
      const router = useRouter()
      const items = ref([
        {
          label: 'Тести'
        },
        {
          label: 'Результати'
        },
        {
          label: 'Користувачі'
        }
      ])

      const logout = () => {
        router.replace('/')
        store.dispatch('auth/logout')
      }

      return {
        items,
        logout
      }
    }
  }
</script>
