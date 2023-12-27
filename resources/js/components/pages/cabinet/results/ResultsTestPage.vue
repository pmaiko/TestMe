<template>
  <DefaultPage
    :breadcrumbs="breadcrumbs"
  >
    <v-container fluid>
      <v-row v-if="!loading">
        <v-col
          v-if="!_get(results, 'length', '')"
          cols="12"
        >
          <VEmpty>
            {{ $t('emptyTests') }}
          </VEmpty>
        </v-col>
        <v-col
          v-else
        >
          <v-row class="flex-wrap">
            <v-col
              v-for="(result, index) in results"
              :key="index"
              md="4"
              sm="6"
              cols="12"
              class="h-auto"
            >
              <router-link :to="{ name: 'results-test-attempt', params: { test_id: testId, attempt: result } }">
                {{ result }}
              </router-link>
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

  const results = ref([])
  const loading = ref(true)

  const testId = useRoute().params.test_id
  const getTestResults = async () => {
    try {
      loading.value = true
      const { data } = await useApi().getTestResults(testId)
      results.value = data
    } catch (error) {
      console.error(error)
    } finally {
      loading.value = false
    }
  }

  onMounted(() => {
    getTestResults()
  })

  const breadcrumbs = computed(() => [
    {
      title: $t('menu.cabinet'),
      disabled: true,
      to: '/cabinet'
    },
    {
      title: $t('menu.results'),
      disabled: false,
      to: { name: 'results' }
    },
    {
      title: $t('test'),
      disabled: true,
      to: { name: 'results-test', params: { test_id: testId } }
    }
  ])
</script>
<style lang="scss">
</style>

