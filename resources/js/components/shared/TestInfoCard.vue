<template>
  <v-card
    elevation="6"
  >
    <template #title>
      <div class="d-flex justify-space-between">
        <!--{{ index + 1 }}.-->
        <span
          class="text-subtitle-1 text-md-h6 text-wrap font-weight-bold"
          v-html="question"
        />

        <FavoriteButton
          :questionId="id"
          :deleteIcon="deleteIcon"
        />
      </div>
    </template>

    <template #subtitle>
      <span class="text-subtitle-1">{{ description }}</span>
    </template>

    <template #text>
      <ul class="pl-8 mb-8">
        <li
          v-for="(item, index) in answers"
          :key="index"
          :class="[
            'text-subtitle-1',
            {'mt-4': index},
            {'text-success': (item.id === _get(answer, 'id', '') && item.correct) || findCorrectById(answers, item.id)},
            {'text-error': item.id === _get(answer, 'id', '') && !item.correct}
          ]"
        >
          <div v-html="item.answer" />
          <div
            v-if="item.description"
            class="text-caption text-grey"
          >
            {{ item.description }}
          </div>
        </li>
      </ul>

      <div
        v-if="answer || answer === null"
        class="pa-2 blue-lighten-4 rounded"
        :class="answer ?
          ([{'bg-success': _get(answer, 'correct', '')},
            {'bg-error': !_get(answer, 'correct', '')}]) : 'bg-grey'"
      >
        <span class="text-subtitle-1">{{ $t('answer') }}:</span> <span
          class="text-subtitle-1 font-weight-bold"
          v-html="_get(answer, 'answer', '') || $t('empty')"
        />
      </div>

      <div class="pa-2 mt-2 bg-yellow-lighten-4 blue-lighten-4 rounded">
        <span class="text-subtitle-1">{{ $t('correctAnswer') }}:</span> <span
          class="text-subtitle-1 font-weight-bold"
          v-html="getCorrect(answers)"
        />
      </div>

      <div
        v-if="diff"
        class="mt-4 text-right"
      >
        <div
          v-if="diff"
          class="text-caption"
        >
          {{ $t('time') }}: <span class="font-weight-bold">{{ diff }}</span>
        </div>

        <!--<div-->
        <!--  v-if="updatedAt"-->
        <!--  class="text-caption"-->
        <!--&gt;-->
        <!--  {{ $t('updatedAt') }}: <span class="font-weight-bold">{{ useFormattedDate(updatedAt).formattedDateTime }}</span>-->
        <!--</div>-->
      </div>
    </template>

    <template
      v-if="$slots.actions"
      #actions
    >
      <slot name="actions" />
    </template>
  </v-card>
</template>
<script setup>
  import FavoriteButton from '~/components/shared/FavoriteButton'
  // import { useFormattedDate } from '~/composables/useDate'

  defineProps({
    id: [String, Number],
    question: String,
    questionId: String,
    description: String,
    answers: Array,
    updatedAt: String,

    answer: Object,
    diff: String,
    deleteIcon: String
  })

  const getCorrect = (answers) => {
    return answers?.reduce((acc, answer) => {
      if (answer.correct) {
        acc.push(answer.answer)
      }
      return acc
    }, []).join(' | ')
  }

  const findCorrectById = (answers, id) => {
    return !!answers?.find((answer) => {
      return answer.correct && answer.id === id
    })
  }
</script>
