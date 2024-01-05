<template>
  <DefaultPage
    :breadcrumbs="breadcrumbs"
  >
    <v-container
      fluid
      class="h-100"
    >
      <v-row v-if="!loading">
        <v-col
          v-if="!_get(favorites, 'length', '')"
          cols="12"
        >
          <VEmpty>
            {{ $t('emptyFavorites') }}
          </VEmpty>
        </v-col>
        <v-col
          v-else
        >
          <v-row class="flex-wrap">
            <v-col
              v-for="(favorite, index) in favorites"
              :key="index"
              md="4"
              sm="6"
              cols="12"
              class="h-auto"
            >
              {{ favorite }}
            </v-col>
          </v-row>
        </v-col>
      </v-row>
      <VLoader
        v-else
      />
    </v-container>
  </DefaultPage>
</template>
<script setup>
  import DefaultPage from '~/components/layout/DefaultPage.vue'
  import VLoader from '~/components/UI/VLoader.vue'
  import VEmpty from '~/components/UI/VEmpty.vue'

  const store = useStore()

  const favorites = computed(() => {
    return store.getters['favorites/favorites']
  })
  const loading = computed(() => {
    return store.getters['favorites/loading']
  })

  onMounted(() => {
    store.dispatch('favorites/fetch')
  })

  const breadcrumbs = computed(() => [
    {
      title: $t('menu.cabinet'),
      disabled: true,
      to: '/cabinet'
    },
    {
      title: $t('menu.favorites'),
      disabled: true,
      to: { name: 'favorites' }
    }
  ])
</script>
<style lang="scss">
</style>

