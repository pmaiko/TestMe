<template>
  <v-container
    fluid
    class="h-100"
  >
    <v-row v-if="!getTestLoading">
      <v-col
        v-if="!testData"
        cols="12"
      >
        <VEmpty>
          {{ $t('error') }}
        </VEmpty>
      </v-col>
      <v-col
        v-else
        cols="12"
      >
        <TestForm
          :title="$t('editTest')"
          :buttonText="$t('updateTest')"
          :errors="errors"
          :loading="testUpdateLoading"
          :fieldsData="fieldsData"
          @submit="onSubmit"
        />

        <v-divider class="my-8" />
        <v-row>
          <v-col
            cols="12"
            class="px-8"
          >
            <p class="text-h6 text-uppercase">
              {{ $t('questions') }}
            </p>
            <v-row>
              <v-col
                cols="12"
              >
                <router-link
                  :to="{ name: 'test-update-question-create', params: { test_id: route.params.test_id } }"
                >
                  <v-btn
                    color="blue"
                    variant="flat"
                    class="text-none mt-4"
                  >
                    <template #prepend>
                      <v-icon icon="mdi mdi-plus" />
                    </template>
                    {{ $t('createQuestion') }}
                  </v-btn>
                </router-link>
              </v-col>
              <v-col
                cols="12"
              >
                <v-expansion-panels v-if="_get(testData, 'questions.length', '')">
                  <v-expansion-panel
                    v-for="question in testData.questions"
                    :key="question.id"
                  >
                    <v-expansion-panel-title>
                      <span class="text-h6">{{ question.question }}</span>
                      <span class="text-grey ml-2">{{ question.description }}</span>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text class="pt-8">
                      <QuestionForm
                        :title="$t('updatingQuestion')"
                        :buttonText="$t('updateQuestion')"
                        :loading="loadingQuestionForm"
                        :errors="errorsQuestionForm"
                        :fieldsData="createFieldsDataQuestionForm(question)"
                        @submit="onSubmitQuestionForm"
                      />
                    </v-expansion-panel-text>
                  </v-expansion-panel>
                </v-expansion-panels>

                <VEmpty v-else>
                  {{ $t('emptyQuestions') }}
                </VEmpty>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    <VLoader
      v-else
    />
  </v-container>
</template>
<script setup>
  import TestForm from '~/components/shared/TestForm'
  import QuestionForm from '~/components/shared/QuestionForm'
  import VEmpty from '~/components/UI/VEmpty'
  import VLoader from '~/components/UI/VLoader'

  import * as api from '~/api'
  import { ref, onMounted } from 'vue'
  import { useRoute } from 'vue-router'

  const route = useRoute()

  const fieldsData = ref(null)
  const getTestLoading = ref(false)
  const testData = ref(null)
  const getTest = async () => {
    try {
      getTestLoading.value = true
      const { data } = await api.test(route.params.test_id)
      fieldsData.value = {
        name: data.name,
        description: data.description
      }
      testData.value = data
    } catch (error) {
      console.error(error)
    } finally {
      getTestLoading.value = false
    }
  }
  onMounted(() => {
    getTest()
  })

  const errors = ref({})
  const testUpdateLoading = ref(false)
  const onSubmit = async (formData) => {
    try {
      testUpdateLoading.value = true
      await api.testUpdate({
        id: route.params.test_id,
        ...formData
      })
    } catch (error) {
      console.error(error)
      errors.value = _get(error, 'response.data.errors') || {}
    } finally {
      testUpdateLoading.value = false
    }
  }

  const errorsQuestionForm = ref({})
  const loadingQuestionForm = ref(false)
  const onSubmitQuestionForm = async (formData) => {
    try {
      loadingQuestionForm.value = true
      await api.questionUpdate({
        test_id: route.params.test_id,
        ...formData
      })
      await getTest()
    } catch (error) {
      console.error(error)
      errors.value = _get(error, 'response.data.errors') || {}
    } finally {
      loadingQuestionForm.value = false
    }
  }
  const createFieldsDataQuestionForm = (question) => {
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
</script>
