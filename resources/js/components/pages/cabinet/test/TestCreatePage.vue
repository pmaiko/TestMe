<template>
  <v-container
    fluid
    class="tests-page h-100"
  >
    <TestForm
      :title="$t('newTest')"
      :buttonText="$t('createTest')"
      :errors="errors"
      :loading="loading"
      @submit="onSubmit"
    />
  </v-container>
</template>
<script setup>
  import * as api from '~/api'
  import TestForm from '~/components/shared/TestForm'

  import { ref } from 'vue'
  import { useRouter } from 'vue-router'

  const router = useRouter()
  const errors = ref({})
  const loading = ref(false)

  const onSubmit = async (formData) => {
    try {
      loading.value = true
      await api.testCreate(formData)
      await router.push({
        name: 'tests'
      })
    } catch (error) {
      console.error(error)
      errors.value = _get(error, 'response.data.errors') || {}
    } finally {
      loading.value = false
    }
  }
</script>
