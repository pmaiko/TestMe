<template>
  <v-row>
    <v-col
      cols="12"
    >
      <p class="text-h6 text-uppercase">
        {{ $t('questions') }}
      </p>
    </v-col>
  </v-row>
  <v-row>
    <v-col
      v-for="(question, index) in _get(props, 'questions', [])"
      :key="index"
      cols="auto"
    >
      <v-btn
        :color="_get(question, 'id', '') === _get(modelValue, 'id', '') ? 'warning': _get(userAnswers[_get(question, 'id', '')], 'status', '')"
        density="compact"
        icon=""
        @click="onClickQuestion(question)"
      >
        {{ index + 1 }}
        <v-tooltip
          activator="parent"
          location="top"
        >
          {{ _get(question, 'question', '') }}
        </v-tooltip>
      </v-btn>
    </v-col>
  </v-row>
</template>
<script setup>
  const props = defineProps({
    questions: Array,
    modelValue: Object,
    userAnswers: Object
  })

  const emit = defineEmits(['update:modelValue'])

  const onClickQuestion = (question) => {
    emit('update:modelValue', question)
  }
</script>
