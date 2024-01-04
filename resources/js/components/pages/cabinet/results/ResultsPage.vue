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
          v-if="!_get(tests, 'length', '')"
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
              v-for="(test, index) in tests"
              :key="index"
              md="4"
              sm="6"
              cols="12"
              class="h-auto"
            >
              <v-card
                class="mx-auto"
                color="primary"
                variant="elevated"
              >
                <v-card-item>
                  <div>
                    <div class="text-overline mb-1">
                      {{ '---' }}
                    </div>
                    <div class="text-h6 mb-1">
                      {{ test.name }}
                    </div>
                    <div class="text-caption">
                      {{ test.description }}
                    </div>
                  </div>
                </v-card-item>

                <v-card-actions>
                  <router-link :to="{ name: 'results-test', params: { testId: test.id }}">
                    <v-btn color="secondary">
                      {{ $t('view') }}
                    </v-btn>
                  </router-link>
                </v-card-actions>
              </v-card>
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

  const tests = ref([])
  const loading = ref(true)

  const getResultsTests = async () => {
    try {
      loading.value = true
      const { data } = await useApi().getResultsTests()
      tests.value = data.data
    } catch (error) {
      console.error(error)
    } finally {
      loading.value = false
    }
  }

  onMounted(() => {
    getResultsTests()
  })

  const breadcrumbs = computed(() => [
    {
      title: $t('menu.cabinet'),
      disabled: true,
      to: '/cabinet'
    },
    {
      title: $t('menu.results'),
      disabled: true,
      to: { name: 'results' }
    }
  ])
</script>
<style lang="scss">
</style>

