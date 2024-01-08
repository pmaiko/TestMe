<template>
  <DefaultPage
    :breadcrumbs="breadcrumbs"
  >
    <v-container
      fluid
      class="flex-1-0 d-flex flex-column"
    >
      <QuestionForm
        v-if="question"
        :type="'update'"
        :title="$t('updatingQuestion')"
        :buttonText="$t('updateQuestion')"
        :loading="loading"
        :errors="errors"
        :fieldsData="createFieldsData(question)"
        @submit="onSubmit"
      />
    </v-container>
  </DefaultPage>
</template>
<script setup>
  import DefaultPage from '~/components/layout/DefaultPage'
  import QuestionForm from '~/components/shared/QuestionForm'

  const router = useRouter()
  const route = useRoute()

  const question = ref(null)
  const errors = ref({})
  const loading = ref(false)
  const showSnackbar = inject('showSnackbar', s => {})

  const getQuestion = async () => {
    try {
      loading.value = true
      errors.value = {}
      const { data } = await useApi().question(route.params.question_id)
      question.value = data.data
    } catch (error) {
      console.error(error)
      showSnackbar($t('error'))
      errors.value = _get(error, 'response.data.errors') || {}
    } finally {
      loading.value = false
    }
  }

  const onSubmit = async (formData) => {
    try {
      loading.value = true
      errors.value = {}
      await useApi().questionUpdate({
        test_id: route.params.test_id,
        ...formData
      })
      showSnackbar($t('updatedQuestion'))
      router.push({ name: 'test-update', params: { test_id: route.params.test_id } })
    } catch (error) {
      console.error(error)
      showSnackbar($t('error'))
      errors.value = _get(error, 'response.data.errors') || {}
    } finally {
      loading.value = false
    }
  }

  const createFieldsData = (question) => {
    const answers = (_get(question, 'answers', '') || []).map(answer => {
      return {
        answer_id: String(answer.id) || '',
        answer: answer.answer || '',
        description: answer.description || '',
        correct: !!answer.correct
      }
    })

    return {
      question_id: String(question.id),
      question: question.question,
      description: question.description,
      answers: answers.length ? answers : null
    }
  }

  onMounted(() => {
    getQuestion()
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
      title: $t('editTest'),
      disabled: false,
      to: { name: 'test-update', params: { test_id: route.params.test_id } }
    },
    {
      title: $t('updatingQuestion'),
      disabled: true,
      to: { name: 'test-update-question-update', params: { test_id: route.params.test_id, question_id: route.params.question_id } }
    }
  ])
</script>
