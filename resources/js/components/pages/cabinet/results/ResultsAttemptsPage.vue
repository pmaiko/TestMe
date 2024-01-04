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
          v-if="!_get(attempts, 'length', '')"
          cols="12"
        >
          <VEmpty>
            {{ $t('emptyAttempts') }}
          </VEmpty>
        </v-col>
        <v-col
          v-else
        >
          <v-table
            fixed-header
            height="100%"
          >
            <thead>
              <tr>
                <th class="text-left">
                  {{ $t('attempt') }}
                </th>
                <th class="text-left">
                  {{ $t('time') }}
                </th>
                <th class="text-left">
                  {{ $t('countQuestions') }}
                </th>
                <th class="text-left">
                  {{ $t('countSuccesses') }}
                </th>
                <th class="text-left">
                  {{ $t('countErrors') }}
                </th>
                <th class="text-left">
                  {{ $t('countMisses') }}
                </th>
                <th class="text-left">
                  {{ $t('percentage') }}
                </th>
                <th class="text-left">
                  {{ '-' }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(attempt, index) in attempts"
                :key="index"
              >
                <td>
                  <router-link :to="{ name: 'results-test-attempt', params: { testId, attemptId: attempt.attemptId } }">
                    {{ attempt.attemptId || '-' }}
                  </router-link>
                </td>
                <td>{{ attempt.time || '-' }}</td>
                <td>{{ attempt.countQuestions || '-' }}</td>
                <td class="text-green font-weight-bold">
                  {{ attempt.countSuccesses || '-' }}
                </td>
                <td class="text-red font-weight-bold">
                  {{ attempt.countErrors || '-' }}
                </td>
                <td class="text-grey font-weight-bold">
                  {{ attempt.countMisses || '-' }}
                </td>
                <td>{{ attempt.percentage }}%</td>
                <td class="text-no-wrap">
                  <router-link :to="{ name: 'results-test-attempt', params: { testId, attemptId: attempt.attemptId } }">
                    {{ useFormattedDate(attempt.createdAt).formattedDateTime || '-' }}
                  </router-link>
                </td>
              </tr>
            </tbody>
          </v-table>
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
  import { useFormattedDate } from '~/composables/useDate'

  const attempts = ref([])
  const loading = ref(true)

  const testId = useRoute().params.testId
  const getResultsAttempts = async () => {
    try {
      loading.value = true
      const { data } = await useApi().getResultsAttempts(testId)
      attempts.value = data.data
    } catch (error) {
      console.error(error)
    } finally {
      loading.value = false
    }
  }

  onMounted(() => {
    getResultsAttempts()
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
      disabled: true,
      to: { name: 'results-test', params: { testId } }
    }
  ])
</script>
<style lang="scss">
</style>

