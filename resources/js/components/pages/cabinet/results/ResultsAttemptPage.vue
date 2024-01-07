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
              cols="12"
              class="mb-8"
            >
              <TestInfoCard
                v-bind="result.question"
                :answers="result.answers"
                :answer="result.answer"
                :diff="result.diff"
              />
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
  import TestInfoCard from '~/components/shared/TestInfoCard.vue'

  const results = ref([])
  const loading = ref(true)

  const testId = useRoute().params.testId
  const attemptId = useRoute().params.attemptId

  const getTestAttempt = async () => {
    try {
      loading.value = true
      const { data } = await useApi().getTestAttempt(testId, attemptId)
      results.value = data.data
    } catch (error) {
      console.error(error)
    } finally {
      loading.value = false
    }
  }

  onMounted(() => {
    getTestAttempt()
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
      title: `${$t('test')} ${testId}`,
      disabled: false,
      to: { name: 'results-test', params: { testId } }
    },
    {
      title: `${$t('attempt')} ${attemptId}`,
      disabled: true,
      to: { name: 'results-test-attempt', params: { testId, attemptId } }
    }
  ])
</script>
<style lang="scss">
</style>

