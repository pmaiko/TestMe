<template>
  <VTitle>
    {{ props.title }}
  </VTitle>
  <v-row>
    <v-col
      sm="6"
      cols="12"
    >
      <v-form @submit.prevent="onSubmit">
        <v-text-field
          v-model="formData['question']"
          :label="$t('question') + '*'"
          :error-messages="errors['question']"
          variant="underlined"
          color="primary"
          name="question"
          type="text"
          class="mb-4"
        />
        <v-textarea
          v-model="formData['description']"
          :label="$t('description')"
          :error-messages="errors['description']"
          variant="underlined"
          color="primary"
          name="description"
          type="text"
          class="mb-4"
        />

        <p class="text-h5 mt-8">
          {{ $t('answers') }}
        </p>
        <v-row>
          <v-col
            v-for="(item, index) in formData['answers']"
            :key="index"
            cols="12"
            class="d-flex flex-column rounded-shaped border-solid pa-6 mx-6 mt-8"
          >
            <v-text-field
              v-model="item['answer']"
              :label="$t('answer') + '*'"
              :error-messages="_get(errors, `answers.${index}.answer`, '')"
              variant="underlined"
              color="primary"
              name="answer"
              type="text"
            />

            <v-row>
              <v-checkbox
                v-model="item['correct']"
                :error-messages="_get(errors, `answers.${index}.correct`, '')"
                :label="$t('correct')"
                name="correct"
              />
            </v-row>

            <v-text-field
              v-if="item['correct']"
              v-model="item['description']"
              :label="$t('description')"
              :error-messages="_get(errors, `answers.${index}.description`, '')"
              variant="underlined"
              color="primary"
              name="description"
              type="text"
            />

            <v-btn
              v-if="_get(formData['answers'], 'length', '') > 1"
              type="button"
              color="red"
              size="small"
              class="mt-4 ml-auto"
              @click="deleteAnswer"
            >
              {{ $t('deleteAnswer') }}
            </v-btn>
          </v-col>
        </v-row>

        <v-row class="mt-4">
          <v-col
            cols="12"
            class="d-flex justify-end"
          >
            <v-btn
              type="button"
              color="primary"
              size="small"
              @click="addAnswer"
            >
              {{ $t('addAnswer') }}
            </v-btn>
          </v-col>
        </v-row>

        <v-row class="mt-8">
          <v-col
            cols="12"
          >
            <v-btn
              :loading="loading"
              type="submit"
              :color="!buttonResetText ? 'green' : null"
              size="x-large"
              class="ma-4"
            >
              {{ props.buttonText }}
            </v-btn>
            <v-btn
              v-if="buttonResetText"
              :loading="loading"
              type="button"
              color="green"
              size="x-large"
              class="ma-4"
              @click="onSubmitReset"
            >
              {{ props.buttonResetText }}
            </v-btn>
          </v-col>
        </v-row>
      </v-form>
    </v-col>
  </v-row>
</template>
<script setup>
  import VTitle from '~/components/UI/VTitle'
  import { reactive } from 'vue'

  const props = defineProps({
    title: String,
    buttonText: String,
    buttonResetText: String,
    errors: Object,
    loading: Boolean,
    fieldsData: Object
  })
  const emit = defineEmits(['submit', 'submitReset'])

  const answer = {
    answer: '',
    description: '',
    correct: false
  }
  const formData = reactive({
    question_id: _get(props.fieldsData, 'question_id', '') || null,
    question: _get(props.fieldsData, 'question', ''),
    description: _get(props.fieldsData, 'description', ''),
    answers: _get(props.fieldsData, 'answers', '') || [
      {
        ...answer
      }
    ]
  })

  const addAnswer = () => {
    formData.answers.push({
      ...answer
    })
  }

  const deleteAnswer = (index) => {
    formData.answers.splice(index, 1)
  }

  const onSubmit = () => {
    emit('submit', formData)
  }

  const onSubmitReset = () => {
    emit('submitReset', formData)
  }
</script>
