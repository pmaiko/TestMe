<template>
  <DefaultPage
    :breadcrumbs="breadcrumbs"
  >
    <v-container
      fluid
    >
      <QuestionForm
        :key="resetKey"
        :type="'create'"
        :title="$t('creatingQuestion')"
        :buttonText="$t('createQuestion')"
        :buttonResetText="$t('createAndAddNew')"
        :loading="loading"
        :errors="errors"
        @submit="onSubmit"
        @submitReset="onSubmitReset"
      />
    </v-container>
  </DefaultPage>
</template>
<script setup>
  import DefaultPage from '~/components/layout/DefaultPage'
  import QuestionForm from '~/components/shared/QuestionForm'

  import * as api from '~/api'

  import { inject, ref, computed } from 'vue'
  import { useRouter, useRoute } from 'vue-router'
  import { useI18n } from 'vue3-i18n'
  const { t: $t } = useI18n()

  const router = useRouter()
  const route = useRoute()

  const resetKey = ref(1)
  const errors = ref({})
  const loading = ref(false)
  const showSnackbar = inject('showSnackbar', s => {})
  const onSubmit = async (formData, onSuccess) => {
    try {
      loading.value = true
      errors.value = {}
      await api.questionCreate({
        test_id: route.params.test_id,
        ...formData
      })
      if (onSuccess) {
        onSuccess()
      } else {
        await router.push({ name: 'test-update', params: { test_id: route.params.test_id } })
      }

      showSnackbar($t('createdQuestion'))
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

  const breadcrumbs = computed(() => [
    {
      title: $t('menu.cabinet'),
      disabled: true,
      to: '/cabinet'
    },
    {
      title: $t('menu.tests'),
      disabled: false,
      to: { name: 'tests' }
    },
    {
      title: $t('editTest'),
      disabled: false,
      to: { name: 'test-update', params: { test_id: route.params.test_id } }
    },
    {
      title: $t('creatingQuestion'),
      disabled: true,
      to: { name: 'test-update-question-create', params: { test_id: route.params.test_id } }
    }
  ])
</script>
