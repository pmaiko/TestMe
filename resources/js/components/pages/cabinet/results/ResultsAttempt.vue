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
            <v-col
              v-for="(item, index) in items"
              :key="item.id"
              cols="12"
              md="10"
              class="mb-8"
            >
              <v-card
                elevation="6"
                class="h-100"
              >
                <template #title>
                  <div class="d-flex justify-space-between text-subtitle-1 text-md-h6 text-wrap font-weight-bold">
                    {{ index + 1 }}. {{ _get(item, 'question.text', '') }}

                    <FavoriteButton
                      :questionId="_get(item, 'question.id', '')"
                    />
                  </div>
                </template>
                <template #subtitle>
                  <span class="text-subtitle-1">{{ _get(item, 'question.description', '') }}</span>
                </template>
                <template #text>
                  <ul class="pl-8">
                    <li
                      v-for="({ id, text, correct, description }, index) in _get(item, 'answers', '')"
                      :key="index"
                      class="text-subtitle-1"
                      :class="[
                        {'mt-4': index},
                        {'text-success': (id === _get(item, 'answer.id', '') && correct) || findCorrectById(_get(item, 'answerCorrect', []), id)},
                        {'text-error': id === _get(item, 'answer.id', '') && !correct}
                      ]"
                    >
                      <div v-html="text" />
                      <div
                        v-if="description"
                        class="text-caption text-grey"
                      >
                        {{ description }}
                      </div>
                    </li>
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
                      v-html="getCorrect(_get(item, 'answerCorrect', []))"
                    />
                  </div>

                  <div class="text-caption float-right mt-4">
                    {{ $t('time') }}: <span class="font-weight-bold">{{ item.diff }}</span>
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
  import FavoriteButton from '~/components/shared/FavoriteButton.vue'

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

  const getCorrect = (answers) => {
    return answers.reduce((acc, answer) => {
      if (answer.correct) {
        acc.push(answer.answer)
      }
      return acc
    }, []).join(' | ')
  }

  const findCorrectById = (answers, id) => {
    return !!answers?.find((answer) => {
      return answer.id === id
    })
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

