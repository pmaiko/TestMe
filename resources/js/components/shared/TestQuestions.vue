<template>
  <div
    v-for="question in questions"
    :key="question.id"
    class="mb-12"
  >
    <TestInfoCard
      v-bind="question"
    >
      <template #actions>
        <v-btn
          color="red"
          variant="flat"
          class="ml-auto mr-4"
          @click.stop="onOpenDialog(question)"
        >
          <v-icon>mdi-trash-can-outline</v-icon>
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
    </TestInfoCard>

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
  import TestInfoCard from '~/components/shared/TestInfoCard'

  const route = useRoute()
  const showSnackbar = inject('showSnackbar', s => {})

  defineProps({
    questions: Array
  })

  const emit = defineEmits(['update'])

  const loading = ref(false)
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
