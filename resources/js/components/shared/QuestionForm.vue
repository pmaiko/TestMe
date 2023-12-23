<template>
  <VTitle>
    {{ props.title }}
  </VTitle>
  <v-row>
    <v-col
      sm="10"
      md="7"
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

        <v-row class="mx-sm-6 mt-8">
          <v-col cols="12">
            <p
              class="text-h5 text-uppercase"
            >
              {{ $t('answers') }}
            </p>
          </v-col>
        </v-row>
        <v-row
          v-if="_get(formData['answers'], 'length', '')"
          class="mx-sm-6"
        >
          <template v-for="(item, index) in formData['answers']">
            <v-col
              v-if="!item.delete"
              :key="index"
              cols="12"
              class="d-flex flex-column rounded-shaped border-solid pa-4 mt-8"
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
                v-if="showButtonDeleteAnswer"
                type="button"
                color="red"
                size="small"
                class="mt-4 ml-auto"
                @click="deleteAnswer(index)"
              >
                {{ $t('deleteAnswer') }}
              </v-btn>
            </v-col>
          </template>
        </v-row>
        <v-row
          v-else
          class="mt-8 mx-sm-6"
        >
          <v-col cols="12">
            <VEmpty>
              {{ $t('emptyAnswers') }}
            </VEmpty>
          </v-col>
        </v-row>

        <v-row class="mt-8 mx-sm-6">
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
          <v-col cols="auto">
            <v-btn
              v-if="buttonResetText"
              :loading="loading"
              type="button"
              color="green"
              size="x-large"
              @click="onSubmitReset"
            >
              {{ props.buttonResetText }}
            </v-btn>
          </v-col>
          <v-col cols="auto">
            <v-btn
              :loading="loading"
              type="submit"
              :color="type === 'update' ? 'green' : null"
              size="x-large"
            >
              {{ props.buttonText }}
            </v-btn>
          </v-col>
        </v-row>
      </v-form>
    </v-col>
  </v-row>
</template>
<script setup>
  import VTitle from '~/components/UI/VTitle'
  import VEmpty from '~/components/UI/VEmpty'

  const props = defineProps({
    type: {
      type: String,
      validator: value => ['create', 'update'].includes(value)
    },
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
      },
      {
        ...answer
      }
    ]
  })

  const showButtonDeleteAnswer = computed(() => {
    let answers = formData.answers
    if (_get(answers, 'length', '')) {
      answers = answers.filter(item => !item.delete)
    }
    console.log(answers)
    return _get(answers, 'length', '') > 2
  })

  const addAnswer = () => {
    formData.answers.push({
      ...answer
    })
  }

  const deleteAnswer = (index) => {
    if (props.type === 'create') {
      formData.answers.splice(index, 1)
    } else if (props.type === 'update') {
      formData.answers = formData.answers.map((item, i) => {
        if (i === index) {
          return {
            delete: true,
            ...item
          }
        }

        return item
      })
    }
  }

  const onSubmit = () => {
    emit('submit', formData)
  }

  const onSubmitReset = () => {
    emit('submitReset', formData)
  }
</script>
