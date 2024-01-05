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
          v-if="!items"
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
            <!--md="4"-->
            <!--sm="6"-->
            <v-col
              v-for="item in items"
              :key="item.id"
              cols="12"
              class="mb-12"
            >
              <v-card
                elevation="6"
                class="h-100"
              >
                <template #title>
                  <span
                    class="text-subtitle-1 text-md-h6 text-wrap font-weight-bold mr-4 clear"
                    v-html="_get(item, 'question.text', '')"
                  />
                  <span class="text-caption float-right">{{ $t('time') }}: {{ item.diff }}</span>
                </template>
                <template #subtitle>
                  <span class="text-subtitle-1">{{ _get(item, 'question.description', '') }}</span>
                </template>
                <template #text>
                  <ul class="pl-8">
                    <li
                      v-for="({ text }, index) in _get(item, 'answers', '')"
                      :key="index"
                      class="text-subtitle-1"
                      :class="{'mt-4': index}"
                      v-html="text"
                    />
                  </ul>
                  <div
                    class="pa-2 mt-8 blue-lighten-4 rounded"
                    :class="_get(item, 'answer', '') ? 
                      ([{'bg-success': _get(item, 'answer.correct', '')},
                        {'bg-error': !_get(item, 'answer.correct', '')}]) : 'bg-grey'"
                  >
                    <span class="text-subtitle-1">{{ $t('answer') }}:</span> <span
                      class="text-subtitle-1 font-weight-bold"
                      v-html="_get(item, 'answer.text', '') || $t('empty')"
                    />
                  </div>

                  <div class="pa-2 mt-2 bg-yellow-lighten-4 blue-lighten-4 rounded">
                    <span class="text-subtitle-1">{{ $t('correctAnswer') }}:</span> <span
                      class="text-subtitle-1 font-weight-bold"
                      v-html="item.answerCorrectText"
                    />
                  </div>
                </template>
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

  const items = ref([])
  const loading = ref(true)

  const testId = useRoute().params.testId
  const attemptId = useRoute().params.attemptId

  const getTestAttempt = async () => {
    try {
      loading.value = true
      const { data } = await useApi().getTestAttempt(testId, attemptId)
      items.value = data.data
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

