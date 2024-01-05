<template>
  <v-row>
    <v-col
      cols="12"
      class="py-0"
    >
      <p class="text-subtitle-1">
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
        :color="_get(question, 'id', '') === _get(modelValue, 'id', '') ? 'primary': _get(userAnswers[_get(question, 'id', '')], 'status', '') || 'grey'"
        icon=""
        :class="{'font-weight-bold': _get(question, 'id', '') === _get(modelValue, 'id', '')}"
        @click="onClickQuestion(question)"
      >
        {{ index + 1 }}
        <!--<v-tooltip-->
        <!--  activator="parent"-->
        <!--  location="top"-->
        <!--&gt;-->
        <!--  {{ _get(question, 'question', '') }}-->
        <!--</v-tooltip>-->
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
