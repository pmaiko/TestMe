<template>
  <DefaultPage
    :breadcrumbs="breadcrumbs"
  >
    <v-container
      fluid
      class="flex-1-0 d-flex flex-column"
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
          <ResultsDashboard
            class="mb-8"
          />

          <ResultsChart
            class="mb-8"
            :attempts="attempts"
          />

          <v-table
            fixed-header
            height="100%"
          >
            <thead>
              <tr>
                <th class="text-left text-no-wrap">
                  {{ $t('attempt') }}
                </th>
                <th class="text-left text-no-wrap">
                  {{ $t('time') }}
                </th>
                <th class="text-left text-no-wrap">
                  {{ $t('countQuestions') }}
                </th>
                <th class="text-left text-no-wrap">
                  {{ $t('countSuccesses') }}
                </th>
                <th class="text-left text-no-wrap">
                  {{ $t('countErrors') }}
                </th>
                <th class="text-left text-no-wrap">
                  {{ $t('countMisses') }}
                </th>
                <th class="text-left text-no-wrap">
                  {{ $t('percentage') }}
                </th>
                <th class="text-left text-no-wrap">
                  {{ $t('date') }}
                </th>
                <th class="text-left text-no-wrap" />
                <th class="text-left text-no-wrap" />
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(attempt, index) in attempts"
                :key="index"
              >
                <td>
                  <router-link
                    :to="{ name: 'results-test-attempt', params: { testId, attemptId: attempt.attemptId } }"
                    class="text-blue text-decoration-none"
                  >
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
                <td class="text-teal font-weight-bold">
                  {{ attempt.percentage }}%
                </td>
                <td class="text-no-wrap">
                  {{ useFormattedDate(attempt.createdAt).formattedDateTime || '-' }}
                </td>
                <td class="text-no-wrap">
                  <router-link
                    :to="{ name: 'results-test-attempt', params: { testId, attemptId: attempt.attemptId } }"
                    class="text-blue text-decoration-none"
                  >
                    {{ $t('details') }}
                  </router-link>
                </td>
                <td class="text-no-wrap">
                  <v-btn
                    icon="mdi-trash-can-outline"
                    color="red"
                    size="x-small"
                    @click="onOpenDialog(attempt)"
                  />
                </td>
              </tr>
            </tbody>
          </v-table>

          <v-dialog
            v-model="dialog"
            persistent
            width="360"
            max-width="100%"
          >
            <v-card>
              <v-card-title class="text-h5 overflow-visible text-wrap">
                {{ $t('reallyWantDeleteAttempt.title') }} - <span class="font-weight-bold">#{{ _get(possibleDeleteAttempt, 'attemptId', '') }}</span>?
              </v-card-title>
              <v-card-text>
                {{ $t('reallyWantDeleteAttempt.description') }}
              </v-card-text>
              <v-card-actions>
                <v-spacer />
                <v-btn
                  color="green-darken-1"
                  variant="text"
                  @click="onCloseDialog"
                >
                  {{ $t('reallyWantDeleteAttempt.no') }}
                </v-btn>
                <v-btn
                  :loading="loading"
                  color="red-darken-1"
                  variant="text"
                  class="font-weight-bold"
                  @click="onDeleteAttempt"
                >
                  {{ $t('reallyWantDeleteAttempt.yes') }}
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
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
  import ResultsDashboard from '~/components/shared/results/ResultsDashboard.vue'
  import ResultsChart from '~/components/shared/results/ResultsChart.vue'

  const attempts = ref([])
  const loading = ref(true)

  const testId = useRoute().params.testId
  const dialog = ref(false)
  const possibleDeleteAttempt = ref(null)

  const getResultsAttempts = async () => {
    try {
      loading.value = true
      const { data: { data } } = await useApi().getResultsAttempts(testId)
      attempts.value = data
    } catch (error) {
      console.error(error)
    } finally {
      loading.value = false
    }
  }

  const deleteTestAttempt = async () => {
    try {
      await useApi().deleteTestAttempt(testId, _get(possibleDeleteAttempt.value, 'attemptId', ''))
    } catch (error) {
      console.error(error)
    }
  }

  const onOpenDialog = (attempt) => {
    dialog.value = true
    possibleDeleteAttempt.value = attempt
  }
  const onCloseDialog = () => {
    dialog.value = false
    possibleDeleteAttempt.value = null
  }

  const onDeleteAttempt = async () => {
    await deleteTestAttempt()
    onCloseDialog()
    await getResultsAttempts()
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

