<template>
  <v-container
    fluid
  >
    <QuestionForm
      :key="resetKey"
      :title="$t('creatingQuestion')"
      :buttonText="$t('createQuestion')"
      :buttonResetText="$t('createAndAddNew')"
      :loading="loading"
      :errors="errors"
      @submit="onSubmit"
      @submitReset="onSubmitReset"
    />
  </v-container>
</template>
<script setup>
  import QuestionForm from '~/components/shared/QuestionForm'
  import * as api from '~/api'

  import { ref } from 'vue'
  import { useRouter, useRoute } from 'vue-router'

  const router = useRouter()
  const route = useRoute()

  const resetKey = ref(1)
  const errors = ref({})
  const loading = ref(false)

  const onSubmit = async (formData, onSuccess) => {
    try {
      loading.value = true
      await api.questionCreate({
        test_id: route.params.test_id,
        ...formData
      })
      await router.push({ name: 'test-update', params: { test_id: route.params.test_id } })
      onSuccess()
    } catch (error) {
      console.error(error)
      errors.value = _get(error, 'response.data.errors') || {}
    } finally {
      loading.value = false
    }
  }

  const onSubmitReset = (formData) => {
    onSubmit(formData, () => {
      resetKey.value++
      window.scrollTo(0, 0)
    })
  }
</script>
