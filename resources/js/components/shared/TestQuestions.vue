<template>
  <v-expansion-panels>
    <v-expansion-panel
      v-for="question in questions"
      :key="question.id"
    >
      <v-expansion-panel-title>
        <span class="text-subtitle-1">{{ question.question }}</span>
        <span class="text-grey ml-2">{{ question.description }}</span>
        <v-btn
          icon="mdi-delete-empty-outline"
          color="red"
          size="x-small"
          variant="flat"
          class="text-none ml-auto mx-2 my-2"
          @click.stop="onOpenDialog(question)"
        />
      </v-expansion-panel-title>
      <v-expansion-panel-text class="pt-8">
        <QuestionForm
          :type="'update'"
          :title="$t('updatingQuestion')"
          :buttonText="$t('updateQuestion')"
          :loading="loading"
          :errors="errors"
          :fieldsData="createFieldsData(question)"
          @submit="onSubmit"
        />
      </v-expansion-panel-text>
    </v-expansion-panel>

    <v-dialog
      v-model="dialog"
      persistent
      width="360"
      max-width="100%"
    >
      <v-card>
        <v-card-title class="text-h5 overflow-visible text-wrap">
          {{ $t('reallyWantDeleteQuestion.title') }} - <span class="font-weight-bold">{{ _get(dialogQuestion, 'question', '') }}</span>?
        </v-card-title>
        <v-card-text>
          {{ $t('reallyWantDeleteQuestion.description') }}
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            color="green-darken-1"
            variant="text"
            @click="onCloseDialog"
          >
            {{ $t('reallyWantDeleteQuestion.no') }}
          </v-btn>
          <v-btn
            :loading="loading"
            color="red-darken-1"
            variant="text"
            class="font-weight-bold"
            @click="onDeleteQuestion"
          >
            {{ $t('reallyWantDeleteQuestion.yes') }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-expansion-panels>
</template>
<script setup>
  import QuestionForm from '~/components/shared/QuestionForm'

  import * as api from '~/api'
  import { inject, ref } from 'vue'
  import { useI18n } from 'vue3-i18n'
  import { useRoute } from 'vue-router'

  const { t: $t } = useI18n()
  const route = useRoute()
  const showSnackbar = inject('showSnackbar', s => {})

  defineProps({
    questions: Array
  })

  const emit = defineEmits(['update'])

  const errors = ref({})
  const loading = ref(false)
  const onSubmit = async (formData) => {
    try {
      loading.value = true
      errors.value = {}
      await api.questionUpdate({
        test_id: route.params.test_id,
        ...formData
      })
      emit('update')
      showSnackbar($t('updatedQuestion'))
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

  const dialog = ref(false)
  const dialogQuestion = ref(null)
  const onOpenDialog = (data) => {
    dialog.value = true
    dialogQuestion.value = data
  }
  const onCloseDialog = () => {
    dialog.value = false
    dialogQuestion.value = null
  }
  const onDeleteQuestion = async () => {
    try {
      loading.value = true

      await api.questionDelete({
        test_id: route.params.test_id,
        question_id: String(dialogQuestion.value.id)
      })
      emit('update')
      showSnackbar($t('deletedQuestion'))
      onCloseDialog()
    } catch (error) {
      showSnackbar($t('error'))
      console.error(error)
    } finally {
      loading.value = false
    }
  }
</script>
<style lang="scss">
  .v-expansion-panel-title {
    &__icon {
      margin-left: 0 !important;
    }
  }
</style>
