<template>
  <v-card
    class="pa-4"
  >
    <v-card-title class="text-h6 font-weight-bold text-none text-wrap">
      {{ currentIndex + 1 }}. {{ _get(question, 'question', '') }} <span
        v-if="String(_get(userAnswer, 'answerId', ''))"
        class="text-blue mdi mdi-check-circle-outline"
      />
    </v-card-title>
    <v-card-subtitle
      v-if="_get(question, 'description', '')"
    >
      {{ _get(question, 'description', '') }}
    </v-card-subtitle>

    <v-radio-group
      :modelValue="_get(userAnswer, 'answerId', '')"
      :class="[
        { 'pointer-events-none-': String(_get(userAnswer, 'answerId', '')) }
      ]"
      class="mt-4 ml-8"
    >
      <div
        v-for="(answer, index) in _get(question, 'answers', [])"
        :key="index"
      >
        <v-radio
          :name="`questionId-${question.id}-answerId-${answer.id}`"
          :label="answer.answer"
          :value="answer.id"
          :color="condition({ answer, success: 'green', error: 'red' })"
          :class="[
            'mt-2',
            condition({ answer, success: 'text-success', error: 'text-error' })
          ]"
          @input="onInput(answer)"
        />
        <span
          v-if="answer.description && condition({ answer, success: 'green', error: 'red' }) === 'green'"
          class="d-block mb-4 ml-10"
        >{{ answer.description }}</span>
      </div>
    </v-radio-group>

    <v-card-actions>
      <v-btn
        :disabled="disabledPrev"
        color="purple-darken-1"
        variant="tonal"
        @click="onPrev"
      >
        {{ $t('back') }}
      </v-btn>
      <v-btn
        :disabled="disabledNext"
        color="pink-darken-1"
        variant="tonal"
        @click="onNext"
      >
        {{ $t('next') }}
      </v-btn>
      <v-btn
        theme="primary"
        variant="tonal"
        class="ml-auto"
      >
        {{ $t('complete') }}
      </v-btn>
    </v-card-actions>
  </v-card>
</template>
<script setup>
  const props = defineProps({
    question: Object,
    userAnswer: Object,
    currentIndex: Number,
    disabledPrev: Boolean,
    disabledNext: Boolean
  })

  const emit = defineEmits(['userRespond', 'prev', 'next'])

  const condition = ({ answer, success, error }) => {
    if (String(_get(props.userAnswer, 'answerId', '')) && answer.correct) {
      return success
    } else if (_get(props.userAnswer, 'answerId', '') === answer.id && !answer.correct) {
      return error
    }
  }
  const onInput = (answer) => {
    if (_get(props.userAnswer, 'answerId', '')) {
      return
    }
    const answerId = _get(answer, 'id', '')

    emit('userRespond', _get(props, 'question.id', ''), {
      answerId,
      status: _get(answer, 'correct', '') ? 'success' : 'error'
    })
  }

  const onPrev = () => {
    emit('prev')
  }

  const onNext = () => {
    emit('next')
  }
</script>
