<template>
  <div
    v-for="question in questions"
    :key="question.id"
  >
    <v-card
      class="mb-12"
      elevation="6"
    >
      <template #title>
        <div class="d-flex justify-space-between">
          <span
            class="text-subtitle-1 text-md-h6 text-wrap font-weight-bold"
            v-html="question.question"
          />

          <FavoriteButton
            :questionId="question.id"
          />
        </div>
      </template>
      <template #subtitle>
        <span class="text-subtitle-1">{{ question.description }}</span>
      </template>
      <template #text>
        <ul class="pl-8">
          <li
            v-for="({ answer, description }, index) in question.answers"
            :key="index"
            class="text-subtitle-1"
            :class="{'mt-4': index}"
          >
            <div v-html="answer" />
            <div
              v-if="description"
              class="text-caption text-grey"
            >
              {{ description }}
            </div>
          </li>
        </ul>
        <div class="d-inline-block pa-2 mt-8 bg-yellow-lighten-4 blue-lighten-4 rounded">
          <span class="text-subtitle-1">{{ $t('correctAnswer') }}:</span> <span
            class="text-subtitle-1 font-weight-bold"
            v-html="getCorrect(question.answers)"
          />
        </div>
      </template>
      <template #actions>
        <v-btn
          color="red"
          variant="flat"
          class="ml-auto mr-4"
          @click.stop="onOpenDialog(question)"
        >
          <v-icon>mdi-delete-empty-outline</v-icon>
          {{ $t('delete') }}
        </v-btn>

        <router-link
          :to="{name: 'test-update-question-update', params: {
            test_id: route.params.test_id,
            question_id: question.id
          }}"
        >
          <v-btn
            color="success"
            variant="flat"
          >
            <v-icon>mdi-pencil</v-icon>
            {{ $t('edit') }}
          </v-btn>
        </router-link>
      </template>
    </v-card>
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
  </div>
</template>
<script setup>
  import FavoriteButton from '~/components/shared/FavoriteButton.vue'

  const route = useRoute()
  const showSnackbar = inject('showSnackbar', s => {})

  defineProps({
    questions: Array
  })

  const emit = defineEmits(['update'])

  const loading = ref(false)
  const dialog = ref(false)
  const dialogQuestion = ref(null)

  const getCorrect = (answers) => {
    return answers.reduce((acc, answer) => {
      if (answer.correct) {
        acc.push(answer.answer)
      }
      return acc
    }, []).join(' | ')
  }

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

      await useApi().questionDelete({
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
