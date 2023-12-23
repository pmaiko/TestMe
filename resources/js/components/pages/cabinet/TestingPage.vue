<template>
  <DefaultPage :breadcrumbs="breadcrumbs">
    <v-container
      fluid
      class="h-100"
    >
      <v-col v-if="!getTestLoading && testData">
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
        />
        <TestingQuestions
          v-model="activeQuestion"
          :questions="questions"
          :userAnswers="userAnswers"
        />
      </v-col>
      <VLoader
        v-else-if="getTestLoading"
      />
      <VEmpty v-else>
        {{ $t('error') }}
      </VEmpty>
    </v-container>
  </DefaultPage>
</template>
<script setup>
  import DefaultPage from '~/components/layout/DefaultPage'
  import VEmpty from '~/components/UI/VEmpty'
  import VLoader from '~/components/UI/VLoader'
  import TestingCard from '~/components/shared/testing/TestingCard'
  import TestingQuestions from '~/components/shared/testing/TestingQuestions'

  import * as api from '~/api'
  import { ref, onMounted, computed } from 'vue'
  import { useRoute } from 'vue-router'
  import { useI18n } from 'vue3-i18n'
  const { t: $t } = useI18n()
  const route = useRoute()

  const activeQuestion = ref(null)

  const userAnswers = ref({})
  const onUserRespond = (questionId, answer) => {
    userAnswers.value[questionId] = answer
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
  const prevQuestion = () => {
    const newIndex = (currentIndex.value - 1 + questions.value.length) % questions.value.length
    activeQuestion.value = questions.value[newIndex] || null
  }

  const nextQuestion = () => {
    const newIndex = (currentIndex.value + 1) % questions.value.length
    activeQuestion.value = questions.value[newIndex] || null
  }
  
  const testData = ref(null)
  const getTestLoading = ref(false)
  const getTest = async () => {
    try {
      getTestLoading.value = true
      const { data } = await api.testing(route.params.test_id)
      testData.value = data
      activeQuestion.value = _get(data, 'questions.0', '') || null
    } catch (error) {
      console.error(error)
    } finally {
      getTestLoading.value = false
    }
  }
  onMounted(() => {
    getTest()
  })

  // const showSnackbar = inject('showSnackbar', s => {})
  // const loading = ref(false)
  // const errors = ref({})
  // const onSubmit = async (formData) => {
  //   try {
  //     loading.value = true
  //     errors.value = {}
  //     const { data } = await api.testUpdate({
  //       id: route.params.test_id,
  //       ...formData
  //     })
  //     showSnackbar(_get(data, 'message', ''))
  //   } catch (error) {
  //     showSnackbar($t('error'))
  //     errors.value = _get(error, 'response.data.errors') || {}
  //     console.error(error)
  //   } finally {
  //     loading.value = false
  //   }
  // }

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
