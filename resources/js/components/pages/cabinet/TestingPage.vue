<template>
  <DefaultPage :breadcrumbs="breadcrumbs">
    <v-container
      fluid
      class="h-100"
    >
      <v-row class="h-100">
        <v-col v-if="!getTestLoading && _get(testData, 'questions.length', '')">
          <TestingCard
            :key="_get(activeQuestion, 'id', '')"
            :question="activeQuestion"
            :currentIndex="currentIndex"
            :userAnswer="userAnswers[_get(activeQuestion, 'id', '')]"
            :disabledPrev="!!disabledPrev"
            :disabledNext="!!disabledNext"
            class="mb-8"
            @userRespond="onUserRespond"
            @prev="prevQuestion"
            @next="nextQuestion"
            @complete="completeTest"
          />
          <TestingQuestions
            v-model="activeQuestion"
            :questions="questions"
            :userAnswers="userAnswers"
            @update:modelValue="scrollTop"
          />

          <v-dialog
            v-model="isResultDialog"
            persistent
            width="auto"
          >
            <v-card>
              <v-card-title class="text-h5">
                {{ $t('result') }}
              </v-card-title>
              <v-card-text>
                <ul>
                  <li>
                    <span>{{ $t('attempt') }}: </span><span class="font-weight-bold">#{{ _get(resultDialogData, 'attemptId', '') }}</span>
                  </li>
                  <li>
                    <span>{{ $t('time') }}: </span><span class="font-weight-bold">{{ _get(resultDialogData, 'time', '') }}</span>
                  </li>
                  <li>
                    <span>{{ $t('percentage') }}: </span><span class="font-weight-bold">{{ _get(resultDialogData, 'percentage', '') }}%</span>
                  </li>
                  <li>
                    <span>{{ $t('countSuccesses') }}: </span><span class="font-weight-bold text-green">{{ _get(resultDialogData, 'countSuccesses', '') }}</span>
                  </li>
                  <li>
                    <span>{{ $t('countErrors') }}: </span><span class="font-weight-bold text-red">{{ _get(resultDialogData, 'countErrors', '') }}</span>
                  </li>
                  <li>
                    <span>{{ $t('countMisses') }}: </span><span class="font-weight-bold text-grey">{{ _get(resultDialogData, 'countMisses', '') }}</span>
                  </li>
                </ul>
              </v-card-text>
              <v-card-actions>
                <router-link
                  :to="{ name: 'tests' }"
                  class="mr-auto"
                >
                  <v-btn
                    color="green-darken-1"
                    variant="flat"
                    @click="isResultDialog = false"
                  >
                    {{ $t('toTests') }}
                  </v-btn>
                </router-link>
                <router-link
                  :to="{ name: 'results-test-attempt', params: { testId: route.params.test_id, attemptId: resultDialogData.attemptId } }"
                  class="ml-8"
                >
                  <v-btn
                    color="blue-darken-1"
                    variant="flat"
                    @click="isResultDialog = false"
                  >
                    {{ $t('toResults') }}
                  </v-btn>
                </router-link>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-col>
        <VLoader
          v-else-if="getTestLoading"
        />
        <VEmpty v-else>
          {{ $t('error') }}
        </VEmpty>
      </v-row>
    </v-container>
  </DefaultPage>
</template>
<script setup>
  import DefaultPage from '~/components/layout/DefaultPage'
  import VEmpty from '~/components/UI/VEmpty'
  import VLoader from '~/components/UI/VLoader'
  import TestingCard from '~/components/shared/testing/TestingCard'
  import TestingQuestions from '~/components/shared/testing/TestingQuestions'

  const route = useRoute()

  const activeQuestion = ref(null)

  const userAnswers = ref({})
  const onUserRespond = (questionId, answer) => {
    userAnswers.value[questionId] = answer

    useApi().setAnswer({
      testId: route.params.test_id,
      questionId: questionId,
      answerId: answer.answerId,
      attemptId: testData.value.attemptId,
      startTime: answer.startTime,
      endTime: answer.endTime
    })
  }

  const questions = computed(() => {
    return _get(testData.value, 'questions', '') || []
  })

  const currentIndex = computed(() => {
    return questions.value.findIndex(item => item.id === _get(activeQuestion.value, 'id', ''))
  })

  const disabledPrev = computed(() => {
    return currentIndex.value < 1
  })
  const disabledNext = computed(() => {
    return currentIndex.value >= questions.value.length - 1
  })

  const isResultDialog = ref(false)
  const resultDialogData = ref(null)

  const prevQuestion = () => {
    const newIndex = (currentIndex.value - 1 + questions.value.length) % questions.value.length
    activeQuestion.value = questions.value[newIndex] || null
  }

  const nextQuestion = () => {
    const newIndex = (currentIndex.value + 1) % questions.value.length
    activeQuestion.value = questions.value[newIndex] || null
  }

  const completeTest = async () => {
    try {
      const { data } = await useApi().completeTest(route.params.test_id, {
        attemptId: _get(testData.value, 'attemptId', '')
      })
      isResultDialog.value = true
      resultDialogData.value = data.data
    } catch (error) {
      console.error(error)
    } finally {
    }
  }
  
  const testData = ref(null)
  const getTestLoading = ref(false)
  const getTest = async () => {
    try {
      getTestLoading.value = true
      const { data: { data } } = await useApi().testing(route.params.test_id, {
        countQuestions: route.query.countQuestions
      })
      testData.value = data
      activeQuestion.value = _get(data, 'questions.0', '') || null
    } catch (error) {
      console.error(error)
    } finally {
      getTestLoading.value = false
    }
  }

  const scrollTop = () => {
    window.scrollTo(0, 0)
  }

  onMounted(() => {
    getTest()
  })

  const breadcrumbs = computed(() => [
    {
      title: $t('menu.cabinet'),
      disabled: true,
      to: '/cabinet'
    },
    {
      title: $t('menu.tests'),
      disabled: false,
      to: { name: 'tests' }
    },
    {
      title: _get(testData.value, 'name', ''),
      disabled: true,
      to: { name: 'testing', params: { test_id: route.params.test_id } }
    }
  ])
</script>
